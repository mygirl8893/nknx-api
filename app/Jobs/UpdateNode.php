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
            $oldOnline = $node->online;
            $oldSversion = $node->sversion;
            if(!$node->alias){
                $node->alias = $node->addr;
            }
            //get main node data
            $requestContent = [
                'timeout' => 1,
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
                $node->online = 1;

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
                    $sversion = substr($response["result"] ,(strpos($response["result"],'v')+1),strpos($response["result"],'-')-(strpos($response["result"],'v')+1));
                    $node->sversion= (int)str_replace('.', '', $sversion);

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

                        if($oldOnline != $node->online || $oldSversion != $node->sversion){
                            $node->notified = null;
                        }
                        $node->save();

                    } catch (RequestException $re) {
                        $node->online = 0;
                        if($node->isDirty()){
                            $node->notified = null;
                            $node->save();
                        }
                    }

                } catch (RequestException $re) {
                    $node->online = 0;
                    if($node->isDirty()){
                        $node->notified = null;
                        $node->save();
                    }
                }

            } catch (RequestException $re) {
                $node->online = 0;
                if($node->isDirty()){
                    $node->notified = null;
                    $node->save();
                }
            }

        }
    }
    public function tags()
    {
        return ['UpdateNode',$this->id];
    }

}
