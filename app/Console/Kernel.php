<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use GuzzleHttp\Client as GuzzleHttpClient;
use GuzzleHttp\Exception\RequestException;

use App\Header;
use App\Block;
use App\Node;
use App\WalletAddress;
use App\Jobs\ProcessRemoteBlock;
use App\Jobs\UpdateNode;
use App\Jobs\UpdateWalletAddress;
use Log;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        //
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {


        $schedule->call(function () {
            //get current blockchain height
            $currentBlockchainHeight = 0;
            $requestContent = [
                'headers' => [
                    'Accept' => 'application/json',
                    'Content-Type' => 'application/json'
                ],
                'json' => [
                    "id" => 1,
                    "method" => "getlatestblockheight",
                    "params" => [
                        "height" => 0,
                    ],
                    "jsonrpc" => "2.0"
                ]
            ];
            try {
                $client = new GuzzleHttpClient();
                $apiRequest = $client->Post('http://testnet-node-0001.nkn.org:30003', $requestContent);        
                $response = json_decode($apiRequest->getBody());
                $currentBlockchainHeight = $response->result;
            } catch (RequestException $re) {
                Log::channel('syncWithBlockchain')->error("Can't connect to testnet-node!");
                throw $re;
            }

            //get latest blockheight in the database
            $localBlockHeight = Header::select('height')->orderBy('height', 'desc')->first();
            if($localBlockHeight){
                $localBlockHeight = $localBlockHeight->height;
            }
            else{
                $localBlockHeight = 0;
            }
            //count all blocks in the database
            $sumLocalBlocks = Block::count();
            // if the local blockchain is consistent
            if ($localBlockHeight===$sumLocalBlocks){
                Log::channel('syncWithBlockchain')->notice('Blockchain is consistent. Fetching new blocks...');
                //push only the new Blocks to the processing queue
                for ($i = $localBlockHeight+1; $i<=$currentBlockchainHeight;$i++){
                    ProcessRemoteBlock::dispatch($i);
                }
            }
            // Else blockchain is not consistent!
            else{
                Log::channel('syncWithBlockchain')->warning('Blockchain is not consistent! Looking for missing blocks...');
                //Run a self-repair
                for ($i = 1; $i<=$sumLocalBlocks;$i++){
                    if (!Header::where('height', '=', $i)->exists()){
                        Log::channel('syncWithBlockchain')->info('Found missing block height: '.$i);
                        //Well... there are less headers than blocks, so get the Hash of the block...
                        $requestContent = [
                            'headers' => [
                                'Accept' => 'application/json',
                                'Content-Type' => 'application/json'
                            ],
                            'json' => [
                                "id" => 1,
                                "method" => "getblock",
                                "params" => [
                                    "height" => (int)$i,
                                ],
                                "jsonrpc" => "2.0"
                            ]
                        ];
                        $client = new GuzzleHttpClient();
                        $apiRequest = $client->Post('http://testnet-node-0001.nkn.org:30003', $requestContent);
                        $response = json_decode($apiRequest->getBody(), true);

                        //so, lets delete the block
                        if(Block::where('hash', '=', $response["result"]["hash"])->delete()){
                            //if it's done we can redownload the block
                            ProcessRemoteBlock::dispatch($i);
                        }
                        else{
                            //okay, now we are fucked...
                            dd("There is something terrible wrong in restoring the blockchain");
                        }
                    }
                }
                //well, Blockchain should be fixed now... so lets test
                $localBlockHeight = Header::select('height')->orderBy('height', 'desc')->first();
                $localBlockHeight = $localBlockHeight->height;
                //count all blocks in the database
                $sumLocalBlocks = Block::count();
                if ($localBlockHeight===$sumLocalBlocks){
                    Log::channel('syncWithBlockchain')->notice('Blockchain is repaired. Start fetching new blocks...');
                    //and start fetching new blocks
                    for ($i = $localBlockHeight+1; $i<=$currentBlockchainHeight;$i++){
                        ProcessRemoteBlock::dispatch($i);
                    }
                }
                else{
                    dd("Blockchain still not consistent!");
                }

            }

            //push fetchBlock Job to queue
        })->everyMinute()->name('SyncWithBlockchain')->withoutOverlapping();

        $schedule->call(function () {
            $nodes= Node::all();
            foreach ($nodes as $node) {
                UpdateNode::dispatch($node->id);
            }
        })->everyMinute()->name('UpdateAllBlocks')->withoutOverlapping();

        $schedule->call(function () {
            $walletAddresses= WalletAddress::all();
            foreach ($walletAddresses as $walletAddress){
                UpdateWalletAddress::dispatch($walletAddress->id);
            }
        })->everyMinute()->name('UpdateAllWalletAddresses')->withoutOverlapping();


    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
