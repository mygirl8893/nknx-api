<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use GuzzleHttp\Client as GuzzleHttpClient;
use GuzzleHttp\Exception\RequestException;

use App\User;
use App\Block;
use App\Node;
use App\Transaction;
use App\AddressBook;
use App\CachedNode;
use App\WalletAddress;
use App\CrawledNode;
use App\CrawlerTempNode;
use App\NotificationsConfig;
use App\Jobs\ProcessRemoteBlock;
use App\Jobs\UpdateNode;
use App\Jobs\UpdateWalletAddress;
use App\Jobs\CleanUpCachedNodes;
use Mail;
use App\Mail\NodeOfflineMail;
use App\Mail\NodeOutdatedMail;
use App\Mail\NodeStuckMail;
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
                    $apiRequest = $client->Post('https://nknx.org:30003', $requestContent);
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

        /*$schedule->call(function () {
            $nodes= Node::get();
            foreach ($nodes as $node) {
                UpdateNode::dispatch($node->id);
            }
        })->everyMinute()->name('UpdateAllNodes')->withoutOverlapping();

        $schedule->call(function () {
            $walletAddresses= WalletAddress::get();
            foreach ($walletAddresses as $walletAddress){
                UpdateWalletAddress::dispatch($walletAddress->id);
            }
        })->everyMinute()->name('UpdateAllWalletAddresses')->withoutOverlapping();

        $schedule->call(function () {

            $newCrawledNodes = [];
            $client = new GuzzleHttpClient();
            $apiRequest = $client->Get('https://testnet.nkn.org/fullnetwork/for_nknx?'.rand());
            $response = json_decode($apiRequest->getBody(), true);

            foreach ($response as $node){
                $tempNode = new CrawledNode([
                    "pk"    =>  $node["nodeId"],
                    "ip"    =>  $node["ip"],
                    "port"  =>  $node["rpcPort"],
                ]);

                //look up the cache
                $cachedNode = CachedNode::where('ip', $tempNode->ip)->first();
                //if node is cached get it
                if($cachedNode){
                    $response = $cachedNode->toArray();
                    $cachedNode->touch();
                }
                //if not ask the api
                else{
                    $client = new GuzzleHttpClient();
                    $apiRequest = $client->Get('https://api.ipgeolocation.io/ipgeo?apiKey='.config('geolocation.ipgeolocation_key').'&ip='.$tempNode->ip);
                    $response = json_decode($apiRequest->getBody(), true);
                    unset($response["ip"]);
                    //update or create cache entry
                    $dbCachedNode = CachedNode::firstOrCreate(array('ip' => $tempNode->ip));
                    $dbCachedNode->fill($response);
                    $dbCachedNode->save();
                }

                //update the values
                $tempNode->fill($response);
                array_push($newCrawledNodes,$tempNode->toArray());
            }
            CrawledNode::truncate();

            foreach (array_chunk($newCrawledNodes,1000) as $t) {
                CrawledNode::insert($t);
            }

        })->everyMinute()->name('CrawlNodes')->withoutOverlapping();

        $schedule->call(function () {
            //get all Users where nodeOffline notifications are turned online
            $users = User::whereHas('notifications_config', function($q){
                $q->where('nodeOffline', 1);
            })
            ->whereHas('nodes', function($q){
                $q->where('online', 0);
            })
            ->with('nodes')
            ->get();

            foreach ($users as $user) {
                $offlineNodes = [];
                foreach ($user->nodes as $node) {
                    if($node->online == 0 && !$node->notified_offline && $node->updated_at <= Carbon::now()->subMinutes(5)){
                        array_push($offlineNodes,$node);
                        $node->notified_offline = Carbon::now();
                        $node->save();
                    }
                }
                if (count($offlineNodes)){
                    Mail::to($user->email)->send(new NodeOfflineMail($user,$offlineNodes));
                }
            }


        })->everyMinute()->name('SendOfflineNotifications')->withoutOverlapping();

        $schedule->call(function () {

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
                $apiRequest = $client->Post('https://nknx.org:30003', $requestContent);
                $response = json_decode($apiRequest->getBody(), true);
                $networkSversion = substr($response["result"] ,(strpos($response["result"],'v')+1),strpos($response["result"],'-')-(strpos($response["result"],'v')+1));
                $networkSversion= (int)str_replace('.', '', $networkSversion);

                //get all Users where nodeOffline notifications are turned online
                $users = User::whereHas('notifications_config', function($q){
                    $q->where('nodeOutdated', 1);
                })
                ->whereHas('nodes', function($q) use($networkSversion){
                    $q->where('sversion','<',$networkSversion);
                })
                ->with('nodes')
                ->get();

                foreach ($users as $user) {
                    $outdatedNodes = [];
                    foreach ($user->nodes as $node) {
                        if($node->online == 1 && !$node->notified_outdated && $node->sversion < $networkSversion){
                            array_push($outdatedNodes,$node);
                            $node->notified_outdated = Carbon::now();
                            $node->save();
                        }
                    }
                    if (count($outdatedNodes)){
                        Mail::to($user->email)->send(new NodeOutdatedMail($user,$outdatedNodes));
                    }
                }
            }
            catch(RequestException $re){

            }


        })->everyMinute()->name('SendOutdatedNotifications')->withoutOverlapping();

        $schedule->call(function () {
            //A node is considered stucked if it is more than 40 Blocks behind of current known blockheight and hasn't been updated for at least 10 minutes
            $latestblock = Block::orderBy('height', 'desc')->first();

            $networkBlockHeight = $latestblock->height;

            //get all Users where nodeOffline notifications are turned online
            $users = User::whereHas('notifications_config', function($q){
                $q->where('nodeOutdated', 1);
            })
            ->whereHas('nodes', function($q){
                $q->where('updated_at', '<', Carbon::now()->subMinutes(10));
            })
            ->with('nodes')
            ->get();

            foreach ($users as $user) {
                $stuckNodes = [];
                foreach ($user->nodes as $node) {
                    if($node->online && $node->updated_at <= Carbon::now()->subMinutes(10) && !$node->notified_stucked && $node->height <= $networkBlockHeight-40){
                        array_push($stuckNodes,$node);
                        $node->notified_stucked = Carbon::now();
                        $node->save();
                    }
                }
                if (count($stuckNodes)){
                    Mail::to($user->email)->send(new NodeStuckMail($user,$stuckNodes));
                }
            }



        })->everyMinute()->name('SendStuckNotifications')->withoutOverlapping();

        $schedule->call(function () {
            CleanUpCachedNodes::dispatch()->onQueue('maintenance');
        })->monthly()->name('CleanUpCachedNodes')->withoutOverlapping();*/
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
