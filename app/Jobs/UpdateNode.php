<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

use GuzzleHttp\Client as GuzzleHttpClient;
use GuzzleHttp\Exception\RequestException;

use App\Node;

use Log;


class UpdateNode implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    protected $id;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($id)
    {
        $this->id = $id;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $node = Node::find($this->id);
       
        if (!$node) {
            
        } else {
            if(!$node->alias){
                $node->alias = $node->addr;
            }
            //get main node data
            $requestContent = [
                'timeout' => 0.5,
                'headers' => [
                    'Accept' => 'application/json',
                    'Content-Type' => 'application/json'
                ],
                'json' => [
                    "id" => 1,
                    "method" => "getnodestate",
                    "params" => [
                        "provider" => "nknx",
                    ],
                    "jsonrpc" => "2.0"
                ]
            ];
            try {
                $client = new GuzzleHttpClient();
                $apiRequest = $client->Post($node->alias . ':30003', $requestContent);        
                $response = json_decode($apiRequest->getBody(), true);
                unset($response["result"]["ID"]);
                $node->fill($response["result"]);
                $node->online = true;

                //get version
                $requestContent = [
                    'headers' => [
                        'Accept' => 'application/json',
                        'Content-Type' => 'application/json'
                    ],
                    'json' => [
                        "id" => 1,
                        "method" => "getversion",
                        "params" => [
                            "provider" => "nknx",
                        ],
                        "jsonrpc" => "2.0"
                    ]
                ];


                try {
                    $client = new GuzzleHttpClient();
                    $apiRequest = $client->Post($node->alias.':30003', $requestContent);        
                    $response = json_decode($apiRequest->getBody(), true);
                    $node->softwareVersion = $response["result"];

                    //get latestblockheight
                    $requestContent = [
                        'headers' => [
                            'Accept' => 'application/json',
                            'Content-Type' => 'application/json'
                        ],
                        'json' => [
                            "id" => 1,
                            "method" => "getlatestblockheight",
                            "params" => [
                                "provider" => "nknx",
                            ],
                            "jsonrpc" => "2.0"
                        ]
                    ];
                    try {
                        $client = new GuzzleHttpClient();
                        $apiRequest = $client->Post($node->alias.':30003', $requestContent);        
                        $response = json_decode($apiRequest->getBody(), true);
                        $node->latestBlockHeight = $response["result"];

                        $node->save();

                        
        
                    } catch (RequestException $re) {
                        $node->online = false;
                        $node->save();
                    }
    
                } catch (RequestException $re) {
                    $node->online = false;
                    $node->save();
                }

            } catch (RequestException $re) {
                $node->online = false;
                $node->save();
            }  

        }
    }
    public function tags()
    {
        return ['UpdateNode',$this->id];
    }
    
}
