<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use GuzzleHttp\Client as GuzzleHttpClient;
use GuzzleHttp\Exception\RequestException;

use App\Block;
use App\Node;
use App\Transaction;
use App\AddressBook;
use App\CachedNode;
use App\WalletAddress;
use App\CrawledNode;
use App\CrawlerTempNode;
use App\Jobs\ProcessRemoteBlock;
use App\Jobs\UpdateNode;
use App\Jobs\UpdateWalletAddress;
use App\Jobs\CleanUpCachedNodes;
use App\Jobs\NodeCrawler;
use Carbon\Carbon;
use Queue;
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
            if (Queue::size('blockchainCrawler') <= 60){
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
                    $apiRequest = $client->Post('http://testnet-seed-0002.nkn.org:30003', $requestContent);
                    $response = json_decode($apiRequest->getBody());
                    $currentBlockchainHeight = $response->result;
                } catch (RequestException $re) {
                    Log::channel('syncWithBlockchain')->error("Can't connect to testnet-node!");
                    throw $re;
                }

                //get latest blockheight in the database
                $localBlockHeight = Block::select('height')->orderBy('height', 'desc')->first();
                if($localBlockHeight){
                    $localBlockHeight = $localBlockHeight->height;
                }
                else{
                    $localBlockHeight = 0;
                }

                Log::channel('syncWithBlockchain')->notice('Fetching new blocks...');
                //push only the new Blocks to the processing queue
                for ($i = $localBlockHeight; $i<=$currentBlockchainHeight;$i++){
                    ProcessRemoteBlock::dispatch($i)->onQueue('blockchainCrawler');
                }
            }
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
            if (Queue::size('nodeCrawler') == 0){
                Log::channel('nodeCrawler')->notice("Node crawling started");
                $seedNodes = array("35.187.201.101","35.198.198.253","146.148.24.130","35.242.233.86");
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
                foreach ($seedNodes as $seedNode){
                    try{
                        $client = new GuzzleHttpClient();
                        $apiRequest = $client->Post($seedNode.':30003', $requestContent);
                        $response = json_decode($apiRequest->getBody(), true);
                        if(array_key_exists("PubKey",$response["result"])){
                            if (!CrawlerTempNode::where('pk', '=',$response["result"]["PubKey"])->exists()) {
                                    $tempNode = new CrawlerTempNode([
                                        "pk"    =>  $response["result"]["PubKey"],
                                        "ip"    =>  $seedNode,
                                        "port"  =>  30003,
                                        "state" =>  0
                                    ]);
                                    $tempNode->save();
                                    NodeCrawler::dispatch($response["result"]["PubKey"],$seedNode,30003)->onQueue('nodeCrawler');
                            }
                        }
                    }
                    catch(RequestException $re){

                    }
                }
            }
        })->everyMinute()->name('CrawlNodes')->withoutOverlapping();

        $schedule->call(function () {
            CleanUpCachedNodes::dispatch()->onQueue('maintenance');
        })->monthly()->name('CleanUpCachedNodes')->withoutOverlapping();
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
