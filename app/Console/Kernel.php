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
use App\CrawledNode;
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
        })->everyMinute()->name('UpdateAllNodes')->withoutOverlapping();

        $schedule->call(function () {
            $walletAddresses= WalletAddress::all();
            foreach ($walletAddresses as $walletAddress){
                UpdateWalletAddress::dispatch($walletAddress->id);
            }
        })->everyMinute()->name('UpdateAllWalletAddresses')->withoutOverlapping();

        $schedule->call(function () {
            $nodes = CrawledNode::all()->pluck('ip')->toArray();
            if(count($nodes)==0){
                $nodes = array("35.197.90.102");
            }
            
            $lastNode = "";
            $blacklist = array();
            $index = 0;
            $retryCount = 0;
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
                        $retryCount = 0;
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
                        $retryCount = 0;
                        $index++;
                    }
                } catch (RequestException $re){
                    //okay, we tried enough - node is offline
                    switch($re->getHandlerContext()['errno']){
                        case 0: 
                            //Log::channel('nodeCrawler')->error('error from ' . $nodes[$index] . ":" . $re->getMessage());
                            array_push($blacklist,$nodes[$index]);
                            array_splice($nodes,$index,1);
                            $retryCount = 0;
                            break;
                        case 28: 
                            //Log::channel('nodeCrawler')->error('error from ' . $nodes[$index] . ":" . $re->getMessage());
                            array_push($blacklist,$nodes[$index]);
                            array_splice($nodes,$index,1);
                            $retryCount = 0;
                            break;
                        case 7:
                            $retryCount++;
                            if($lastNode == $nodes[$index]){
                                array_push($blacklist,$nodes[$index]);
                                array_splice($nodes,$index,1);
                                $retryCount = 0;
                            }
                            else{
                                //Log::channel('nodeCrawler')->error('connection refused from ' . $nodes[$index] . "!");

                                //Log::channel('nodeCrawler')->error($nodes[$index] . " has to cool down - moving at the end of list");
                                //end of list
                                $lastNode = $nodes[$index];
                                array_push($nodes,$nodes[$index]);
                                array_splice($nodes,$index,1);
                                $retryCount = 0;
                            }
                            


                            break;
                        default:
                            //Log::channel('nodeCrawler')->error('error from ' . $nodes[$index] . ":" . $re->getMessage());
                            array_push($blacklist,$nodes[$index]);
                            array_splice($nodes,$index,1);
                            $retryCount = 0;
                            break;
                    }

                }
            }
            CrawledNode::truncate();
            foreach ($nodes as $node){
                CrawledNode::create(array('ip' => $node));
            }
            dd(count($nodes));
        })->everyFifteenMinutes()->name('CrawlNodes')->withoutOverlapping();


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
