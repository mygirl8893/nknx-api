<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use GuzzleHttp\Client as GuzzleHttpClient;
use GuzzleHttp\Exception\RequestException;
use Log;
use App\CrawledNode;
use App\CachedNode;


class NodeCrawler implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Log::channel('nodeCrawler')->notice("Node crawling started");
        $nodes = CrawledNode::all()->pluck('ip')->toArray();
        if(count($nodes)==0){
            $nodes = array("35.197.90.102","35.187.152.66","35.187.201.101","35.198.198.253","146.148.24.130","35.242.233.86","35.204.197.53","35.192.235.226");
        }
        
        $lastNode = "";
        $blacklist = array();
        $retries = array();
        $index = 0;

        $requestContent = [
            'timeout' => 1,
            'headers' => [
                'Accept' => 'application/json',
                'Content-Type' => 'application/json'
            ],
            'json' => [
                "id" => 1,
                "method" => "getneighbor",
                "params" => [
                    "provider" => "nknx",
                ],
                "jsonrpc" => "2.0"
            ]
        ];

        while($index <=  (count($nodes)-1)){
            $client = new GuzzleHttpClient();
            //Log::channel('nodeCrawler')->notice($index.":".count($nodes));
            try {
                if(!filter_var($nodes[$index], FILTER_VALIDATE_IP)){
                    //Log::channel('nodeCrawler')->warning('invalid ip!');
                    array_splice($nodes,$index,1);
                }
                else{
                    $apiRequest = $client->Post('http://'.$nodes[$index].':30003', $requestContent); 
                    $response = json_decode($apiRequest->getBody(), true);
                    $neighbors = $response["result"];
                    if(is_array($neighbors)){
                        foreach ($neighbors as $neighbor){
                            $count= count($neighbor["IpAddr"])-1;
                            $host = $neighbor["IpAddr"][$count-3].".".$neighbor["IpAddr"][$count-2].".".$neighbor["IpAddr"][$count-1].".".$neighbor["IpAddr"][$count];
                            if(!in_array($host, $nodes)&&!in_array($host, $blacklist)){
                                array_push($nodes,$host);
                            }
                        }
                    }
                    $index++;
                }
            } catch (RequestException $re){
                //okay, we tried enough - node is offline
                switch($re->getHandlerContext()['errno']){
                    case 0: 
                        //Log::channel('nodeCrawler')->error('error from ' . $nodes[$index] . ":" . $re->getMessage());
                        array_push($blacklist,$nodes[$index]);
                        array_splice($nodes,$index,1);
                        break;
                    case 28: 
                        //Log::channel('nodeCrawler')->error('error from ' . $nodes[$index] . ":" . $re->getMessage());
                        array_push($blacklist,$nodes[$index]);
                        array_splice($nodes,$index,1);
                        break;
                    case 7:
                        if($lastNode == $nodes[$index]){
                            array_push($blacklist,$nodes[$index]);
                            array_splice($nodes,$index,1);
                        }
                        else{
                            //Log::channel('nodeCrawler')->error('connection refused from ' . $nodes[$index] . "!");
                            $count = 0;
                            foreach ($retries as $item) {
                                if ($item == $nodes[$index]) {
                                    $count++;
                                }
                            }
                            if($count >=10){
                                //Log::channel('nodeCrawler')->error('Too many retries for '.$nodes[$index] . " ignoring its neighbors.");
                                array_push($blacklist,$nodes[$index]);
                                $index++;
                            }
                            else{

                                //Log::channel('nodeCrawler')->error($nodes[$index] . " has to cool down - moving at the end of list");
                                //end of list
                                $lastNode = $nodes[$index];
                                array_push($nodes,$nodes[$index]);
                                array_push($retries,$nodes[$index]);
                                array_splice($nodes,$index,1);
                            }

                        }
                        break;
                    default:
                        //Log::channel('nodeCrawler')->error('error from ' . $nodes[$index] . ":" . $re->getMessage());
                        array_push($blacklist,$nodes[$index]);
                        array_splice($nodes,$index,1);
                        break;
                }

            }
            
        }

        //delete all old nodes
        CrawledNode::whereNotIn('ip', $nodes)->delete();

        //update or create new one
        foreach ($nodes as $node){
            CrawledNode::updateOrCreate(array('ip' => $node));
            //if the ip is in the cache refresh it
            $tempDbCachedNode = CachedNode::where('ip', $node)->first();
            if($tempDbCachedNode){
                $tempDbCachedNode->touch();
            }
        }

        //get new Nodes without geolocation
        $newDbNodes = CrawledNode::whereNull('latitude')->pluck('ip')->toArray();

        //for each of them
        foreach ($newDbNodes as $newDbNode){
            //look up the cache
            $cachedNode = CachedNode::where('ip', $newDbNode)->first();

            //if node is cached get it
            if($cachedNode){
                $cachedNode = $cachedNode->toArray();
                $response = $cachedNode;
            }
            //if not ask the api
            else{
                $client = new GuzzleHttpClient();
                $apiRequest = $client->Get('https://api.ipgeolocation.io/ipgeo?apiKey='.config('geolocation.ipgeolocation_key').'&ip='.$newDbNode);
                $response = json_decode($apiRequest->getBody(), true);
                unset($response["ip"]);
            }
            
            //update the values
            $updatedDbNode = CrawledNode::where('ip', $newDbNode)->first();
            $updatedDbNode->fill($response);
            $updatedDbNode->save();
            //update or create cache entry
            $response["ip"] = $newDbNode;

            $dbCachedNode = CachedNode::firstOrCreate(array('ip' => $newDbNode));
            $dbCachedNode->fill($response);
            $dbCachedNode->save();


        }
        Log::channel('nodeCrawler')->notice("Node crawling successful");
    }
}
