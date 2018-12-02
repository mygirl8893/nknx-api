<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\AddressBook;
use GuzzleHttp\Client as GuzzleHttpClient;
use GuzzleHttp\Exception\RequestException;

class CreateAddressBookItem implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    protected $name;
    protected $registrant;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($name, $registrant)
    {
        $this->name = $name;
        $this->registrant = $registrant;
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
                "method" => "getaddressbyname",
                "params" => [
                    "name" => $this->name,
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
                    "public_key" => $this->registrant
                ],[
                    "name" => $this->name,
                    "address" => $response["result"]
                ]);
                $responseItem->save();
            }
            
        }
        catch(RequestException $re) {

        }
    }
    public function tags()
    {
        return ['CreateAddressBookItem',$this->registrant, $this->name];
    }
}
