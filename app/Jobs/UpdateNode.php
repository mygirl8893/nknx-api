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
use App\Block;
use Carbon\Carbon;
use GuzzleHttp\TransferStats;

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
            $time = 0.1;
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
                ],
                'on_stats' => function (TransferStats $stats) use (&$time){
                    $time = $stats->getTransferTime();

                }
            ];
            try {
                $client = new GuzzleHttpClient();
                $apiRequest = $client->Post($node->alias . ':30003', $requestContent);
                $response = json_decode($apiRequest->getBody(), true);
                unset($response["result"]["id"]);
                $node->fill($response["result"]);
                $node->latestBlockHeight = $response["result"]["height"];
                $node->latency = $time;
                $node->online = 1;
                $sversion = substr($response["result"]["version"] ,(strpos($response["result"]["version"],'v')+1),strpos($response["result"]["version"],'-')-(strpos($response["result"]["version"],'v')+1));
                $node->sversion= (int)str_replace('.', '', $sversion);

                //if node switches version reset notification
                if($oldSversion != $node->sversion){
                    $node->notified_outdated = null;
                }
                //if node switches from offline to online reset notification
                if($oldOnline != $node->online ){
                    $node->notified_offline = null;
                }

                $latestblock = Block::orderBy('height', 'desc')->first();
                if($node->updated_at > Carbon::now()->subMinutes(10) || $node->height > $latestblock->height-40){
                    $node->notified_stucked = null;
                }
                $node->save();



            } catch (RequestException $re) {
                $node->online = 0;
                if($node->isDirty()){
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
