<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

use GuzzleHttp\Client as GuzzleHttpClient;
use GuzzleHttp\Exception\RequestException;

use App\WalletAddress;

use Log;#


class UpdateWalletAddress implements ShouldQueue
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
        $walletAddress = WalletAddress::find($this->id);
       
        if (!$walletAddress) {
            
        } else {
            $balance = 0;
            $requestContent = [
                'headers' => [
                    'Accept' => 'application/json',
                    'Content-Type' => 'application/json'
                ],
                'json' => [
                    "id" => 1,
                    "method" => "getunspendoutput",
                    "params" => [
                        "address" => $walletAddress->address, 
                        "assetid" => "4945ca009174097e6614d306b66e1f9cb1fce586cb857729be9e1c5cc04c9c02"
                    ],
                    "jsonrpc" => "2.0"
                ]
            ];

            try {
                $client = new GuzzleHttpClient();
                $apiRequest = $client->Post('http://testnet-node-0001.nkn.org:30003', $requestContent);        
                $response = json_decode($apiRequest->getBody(), true);

                foreach($response["result"] AS $unspendoutput) {
                    $balance = $balance + $unspendoutput["Value"];
                }
                $walletAddress->balance = $balance; 
                $walletAddress->save();

            } catch (RequestException $re) {
                //maybe some good error?!
            }

        }
    }
    
}
