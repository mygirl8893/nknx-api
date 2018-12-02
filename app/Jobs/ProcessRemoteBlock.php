<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

use GuzzleHttp\Client as GuzzleHttpClient;
use GuzzleHttp\Exception\RequestException;

use App\Block;
use App\Transaction;
use App\Attribute;
use App\Output;
use App\Input;
use App\Payload;
use App\Jobs\CreateAddressBookItem;
use App\Jobs\DeleteAddressBookItem;
use Log;


class ProcessRemoteBlock implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    protected $blockheight;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($blockheight)
    {
        $this->blockheight = $blockheight;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        //if block isn't in the database get it and parse it (to prevent race conditions)
        if (!Block::where('height', '=', $this->blockheight)->exists()){
            $requestContent = [
                'headers' => [
                    'Accept' => 'application/json',
                    'Content-Type' => 'application/json'
                ],
                'json' => [
                    "id" => 1,
                    "method" => "getblock",
                    "params" => [
                        "height" => (int)$this->blockheight,
                    ],
                    "jsonrpc" => "2.0"
                ]
            ];
            
            try {
                $client = new GuzzleHttpClient();
        
                $apiRequest = $client->Post('http://testnet-seed-0002.nkn.org:30003', $requestContent);
                
                $response = json_decode($apiRequest->getBody(), true);
                $blockdata = array_merge($response["result"]["header"]["program"], $response["result"]["header"], $response["result"]);
                
                
                $previousBlock = Block::where('hash',$blockdata['prevBlockHash'])->first();
                if($previousBlock){
                    $previousBlock->nextBlockHash = $blockdata['hash'];
                    $previousBlock->save();
                }


                $block = new Block($blockdata);
                $block->transaction_count = count($response["result"]["transactions"]);
                $block->save();
                foreach($response["result"]["transactions"] as $transaction) {
                    $attributes = [];
                    $outputs = [];
                    $inputs = [];
                
                    foreach((array)$transaction["attributes"] as $attribute){
                        $attributes[] = new Attribute($attribute);
                    }
                    
                    foreach((array)$transaction["outputs"] as $output){
                        $outputs[] = new Output($output);
                    }

                    foreach((array)$transaction["inputs"] as $input){
                        $inputs[] = new Input($input);
                        if($transaction["txType"]==16){
                            $requestContentTransaction = [
                                'headers' => [
                                    'Accept' => 'application/json',
                                    'Content-Type' => 'application/json'
                                ],
                                'json' => [
                                    "id" => 1,
                                    "method" => "gettransaction",
                                    "params" => [
                                        "hash" => $input['referTxID'],
                                    ],
                                    "jsonrpc" => "2.0"
                                ]
                            ];
                            $senderRequest = $client->Post('http://testnet-seed-0002.nkn.org:30003', $requestContentTransaction);
                            $transactionResponse = json_decode($senderRequest->getBody(), true);
                            $transaction["sender"] = $transactionResponse["result"]["outputs"][$input['referTxOutputIndex']]['address'];
                        }
                    }

                    $newTransaction = $block->transactions()->save(new Transaction($transaction));

                    $newTransaction->attributes()->saveMany($attributes);
                    $newTransaction->outputs()->saveMany($outputs);
                    $newTransaction->inputs()->saveMany($inputs);
                    if(!empty($transaction["payload"])) {
                        $newTransaction->payload()->save(new Payload($transaction["payload"]));
                        if($transaction["txType"]==80){
                            CreateAddressBookItem::dispatch($transaction["payload"]["name"],$transaction["payload"]["registrant"]);
                        }
                        else if($transaction["txType"]==82){
                            DeleteAddressBookItem::dispatch($transaction["payload"]["registrant"]);
                        }
                    }
                }


        
            } catch (RequestException $re) {
                Log::channel('syncWithBlockchain')->error("ProcessRemoteBlock: Can't connect to testnet-node!");
                throw $re; 
            }
        }
    }
    public function tags()
    {
        return ['ProcessRemoteBlock',$this->blockheight];
    }
}
