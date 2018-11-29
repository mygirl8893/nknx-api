<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use GuzzleHttp\Client as GuzzleHttpClient;
use GuzzleHttp\Exception\RequestException;

use App\Header;
use App\Block;
use App\Node;
use App\Transaction;
use App\AddressBook;
use App\CachedNode;
use App\WalletAddress;
use App\CrawledNode;
use App\Jobs\ProcessRemoteBlock;
use App\Jobs\UpdateNode;
use App\Jobs\UpdateWalletAddress;
use Carbon\Carbon;
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
                $apiRequest = $client->Post('http://testnet-seed-0002.nkn.org:30003', $requestContent);        
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
                        $apiRequest = $client->Post('http://testnet-seed-0002.nkn.org:30003', $requestContent);
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
            Log::channel('nodeCrawler')->notice("Node crawling started");
            $nodes = CrawledNode::all()->pluck('ip')->toArray();
            if(count($nodes)==0){
                $nodes = array("35.197.90.102","35.187.152.66","35.187.201.101","35.198.198.253","146.148.24.130","35.242.233.86","35.204.197.53","35.192.235.226");
            }
            
            $lastNode = "";
            $blacklist = array();
            $retries = array();
            $index = 0;

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
                        $index++;
                    }
                } catch (RequestException $re){
                    //okay, we tried enough - node is offline
                    switch($re->getHandlerContext()['errno']){
                        case 0: 
                            //Log::channel('nodeCrawler')->error('error from ' . $nodes[$index] . ":" . $re->getMessage());
                            array_push($blacklist,$nodes[$index]);
                            array_splice($nodes,$index,1);
                            break;
                        case 28: 
                            //Log::channel('nodeCrawler')->error('error from ' . $nodes[$index] . ":" . $re->getMessage());
                            array_push($blacklist,$nodes[$index]);
                            array_splice($nodes,$index,1);
                            break;
                        case 7:
                            if($lastNode == $nodes[$index]){
                                array_push($blacklist,$nodes[$index]);
                                array_splice($nodes,$index,1);
                            }
                            else{
                                //Log::channel('nodeCrawler')->error('connection refused from ' . $nodes[$index] . "!");
                                $count = 0;
                                foreach ($retries as $item) {
                                    if ($item == $nodes[$index]) {
                                        $count++;
                                    }
                                }
                                if($count >=10){
                                    //Log::channel('nodeCrawler')->error('Too many retries for '.$nodes[$index] . " ignoring its neighbors.");
                                    array_push($blacklist,$nodes[$index]);
                                    $index++;
                                }
                                else{

                                    //Log::channel('nodeCrawler')->error($nodes[$index] . " has to cool down - moving at the end of list");
                                    //end of list
                                    $lastNode = $nodes[$index];
                                    array_push($nodes,$nodes[$index]);
                                    array_push($retries,$nodes[$index]);
                                    array_splice($nodes,$index,1);


                                }

                            }
                            break;
                        default:
                            //Log::channel('nodeCrawler')->error('error from ' . $nodes[$index] . ":" . $re->getMessage());
                            array_push($blacklist,$nodes[$index]);
                            array_splice($nodes,$index,1);
                            break;
                    }

                }
                
            }

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
            Log::channel('nodeCrawler')->notice("Node crawling successful");

        })->everyMinute()->name('CrawlNodes')->withoutOverlapping();

        $schedule->call(function () {
            $addressBookItems = array();

            $createdWalletNames = Transaction::where('txType',80)
                ->with('payload')
                ->orderBy('id', 'asc')
                ->get()
                ->toArray();
            $deletedWalletNames = Transaction::where('txType',82)
                ->with('payload')
                ->orderBy('id', 'asc')
                ->get()
                ->toArray();
    
            foreach($deletedWalletNames as $deletedWalletName){
                $i = 0;
                while( $i<count($createdWalletNames)){
                    if($createdWalletNames[$i]["payload"]["registrant"] == $deletedWalletName["payload"]["registrant"]){
                        array_splice($createdWalletNames,$i,1);
                        break;
                    }
                    $i++;
                }
            }
    
            foreach($createdWalletNames as $createdWalletName){
                $requestContent = [
                    'headers' => [
                        'Accept' => 'application/json',
                        'Content-Type' => 'application/json'
                    ],
                    'json' => [
                        "id" => 1,
                        "method" => "getaddressbyname",
                        "params" => [
                            "name" => $createdWalletName["payload"]["name"],
                        ],
                        "jsonrpc" => "2.0"
                    ]
                ];
                
                try {
                    $client = new GuzzleHttpClient();
            
                    $apiRequest = $client->Post('http://testnet-seed-0002.nkn.org:30003', $requestContent);
                    
                    $response = json_decode($apiRequest->getBody(), true);
                    if(array_key_exists('result', $response)){
                        $responseItem = AddressBook::updateOrCreate([
                            "address" => $response["result"]
                        ],[
                            "name" => $createdWalletName["payload"]["name"],
                            "private_key" => $createdWalletName["payload"]["registrant"],
                        ]);
                        $responseItem->save();
                    }
                    
                }
                catch(RequestException $re) {

                }
            }




        })->everyMinute()->name('CreateAddressBook')->withoutOverlapping();

        $schedule->call(function () {
            CachedNode::where('updated_at', '<', Carbon::now()->subMonth())->delete();
        })->monthly()->name('CleanUpCachedNodes'); 


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
