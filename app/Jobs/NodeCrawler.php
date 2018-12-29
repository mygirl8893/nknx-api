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
use App\CrawlerTempNode;
use App\Jobs\NodeCrawler;
use Queue;

class NodeCrawler implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    protected $pk;
    protected $ip;
    protected $port;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($pk,$ip,$port)
    {
        $this->pk = $pk;
        $this->ip = $ip;
        $this->port = $port;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        //check if IP is correct
        if(filter_var($this->ip, FILTER_VALIDATE_IP)){
            //only process it if it hasn't been checked by another process
            $crawlerTempNode = CrawlerTempNode::where([['pk', '=',$this->pk],['state', '=',0]])->first();
            if ($crawlerTempNode) {
                $crawlerTempNode->state = 1;
                //if not try to reach it
                $requestContent = [
                    'timeout' => 2,
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
                try{
                    $client = new GuzzleHttpClient();
                    $apiRequest = $client->Post($this->ip.':'.$this->port, $requestContent);
                    $response = json_decode($apiRequest->getBody(), true);

                    //try to save it
                    if($crawlerTempNode->save()){
                        $neighbors = $response["result"];
                        //if it is saved we can get the geolocation
                        //look up the cache
                        $cachedNode = CachedNode::where('ip', $this->ip)->first();

                        //if node is cached get it
                        if($cachedNode){
                            $response = $cachedNode->toArray();
                            $cachedNode->touch();
                        }
                        //if not ask the api
                        else{
                            $client = new GuzzleHttpClient();
                            $apiRequest = $client->Get('https://api.ipgeolocation.io/ipgeo?apiKey='.config('geolocation.ipgeolocation_key').'&ip='.$this->ip);
                            $response = json_decode($apiRequest->getBody(), true);
                            unset($response["ip"]);
                            //update or create cache entry
                            $dbCachedNode = CachedNode::firstOrCreate(array('ip' => $this->ip));
                            $dbCachedNode->fill($response);
                            $dbCachedNode->save();
                        }

                        //update the values
                        $crawlerTempNode->fill($response);
                        $crawlerTempNode->save();


                        if(is_array($neighbors)){
                            $i = 0;
                            foreach ($neighbors as $neighbor){
                                $pubkey = $neighbor["NKNaddr"];
                                $host = $neighbor["IpStr"];
                                $port = 30003;
                                if (!CrawlerTempNode::where('pk', '=',$pubkey)->exists()) {
                                    $tempNode = new CrawlerTempNode([
                                        "pk"    =>  $pubkey,
                                        "ip"    =>  $host,
                                        "port"  =>  $port,
                                        "state" =>  0
                                    ]);
                                    $tempNode->save();
                                    NodeCrawler::dispatch($pubkey,$host,$port)->onQueue('nodeCrawler');
                                   $i++;
                                }

                            }
                            if($i==0){
                                //check if queue is empty
                                if (Queue::size('nodeCrawler') == 1){
                                    //wait for random seconds
                                    usleep(rand(500000,3000000));
                                    //if queue is still empty
                                    if (Queue::size('nodeCrawler') == 1){
                                        CrawledNode::truncate();
                                        $finalNodes = CrawlerTempNode::where('state', 1)->get();
                                        foreach ($finalNodes as $finalNode){
                                            $finalCrawledNode = new CrawledNode($finalNode->toArray());
                                            $finalCrawledNode->save();
                                        }
                                        Log::channel('nodeCrawler')->notice("Node crawling finished");
                                        CrawlerTempNode::truncate();
                                    }
                                }
                            }
                        }
                        else{
                            //check if queue is empty
                            if (Queue::size('nodeCrawler') == 1){
                                //wait for random seconds
                                usleep(rand(500000,3000000));
                                //if queue is still empty
                                if (Queue::size('nodeCrawler') == 1){
                                    CrawledNode::truncate();
                                    $finalNodes = CrawlerTempNode::where('state', 1)->get();
                                    foreach ($finalNodes as $finalNode){
                                        $finalCrawledNode = new CrawledNode($finalNode->toArray());
                                        $finalCrawledNode->save();
                                    }
                                    Log::channel('nodeCrawler')->notice("Node crawling finished");
                                    CrawlerTempNode::truncate();
                                }
                            }
                        }
                    }
                    else{
                        //check if queue is empty
                        if (Queue::size('nodeCrawler') == 1){
                            //wait for random seconds
                            usleep(rand(500000,3000000));
                            //if queue is still empty
                            if (Queue::size('nodeCrawler') == 1){
                                CrawledNode::truncate();
                                $finalNodes = CrawlerTempNode::where('state', 1)->get();
                                foreach ($finalNodes as $finalNode){
                                    $finalCrawledNode = new CrawledNode($finalNode->toArray());
                                    $finalCrawledNode->save();
                                }
                                Log::channel('nodeCrawler')->notice("Node crawling finished");
                                CrawlerTempNode::truncate();
                            }
                        }
                    }

                }
                catch(RequestException $re){
                    $crawlerTempNode->state = 2;
                    $crawlerTempNode->save();
                    //check if queue is empty
                    if (Queue::size('nodeCrawler') == 1){
                        //wait for random seconds
                        usleep(rand(500000,3000000));
                        //if queue is still empty
                        if (Queue::size('nodeCrawler') == 1){
                            CrawledNode::truncate();
                            $finalNodes = CrawlerTempNode::where('state', 1)->get();
                            foreach ($finalNodes as $finalNode){
                                $finalCrawledNode = new CrawledNode($finalNode->toArray());
                                $finalCrawledNode->save();
                            }
                            Log::channel('nodeCrawler')->notice("Node crawling finished");
                            CrawlerTempNode::truncate();
                        }
                    }
                }
            }
        }


       /*

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
        Log::channel('nodeCrawler')->notice("Node crawling successful");*/
    }
   // public function tags()
   // {
   //     return ['NodeCrawler'];
   // }
}
