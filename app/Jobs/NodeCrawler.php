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

                            foreach ($neighbors as $neighbor){
                                $pubkey = $neighbor["id"];
                                preg_match('/\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}/', $neighbor["addr"], $matches);
                                $host = $matches[0];
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
                                }
                            }
                        }
                        else{
                            //
                        }
                    }
                    else{
                       //
                    }

                }
                catch(RequestException $re){
                    $crawlerTempNode->state = 2;
                    $crawlerTempNode->save();
                    //
                }
            }
        }
    }
   // public function tags()
   // {
   //     return ['NodeCrawler'];
   // }
}
