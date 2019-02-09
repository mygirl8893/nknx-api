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
use App\NodeTracing;
use App\CrawledNode;
use App\Jobs\CreateAddressBookItem;
use App\Jobs\DeleteAddressBookItem;

use Log;


class FillChordID implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    protected $blockObject;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($blockObject)
    {
        $this->blockObject = $blockObject;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $requestContent = [
            'headers' => [
                'Accept' => 'application/json',
                'Content-Type' => 'application/json'
            ],
            'json' => [
                "id" => 1,
                "method" => "getblock",
                "params" => [
                    "height" => (int)$this->blockObject->height,
                ],
                "jsonrpc" => "2.0"
            ]
        ];

        try {
            $client = new GuzzleHttpClient();

            $apiRequest = $client->Post('https://nknx.org:30003', $requestContent);

            $response = json_decode($apiRequest->getBody(), true);
            $this->blockObject->chordID = $response["result"]["header"]["chordID"];
            $this->blockObject->save();
        }
        catch (RequestException $re) {
            throw $re;
        }

    }
}
