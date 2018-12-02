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

class DeleteAddressBookItem implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    protected $registrant;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($registrant)
    {
        $this->registrant = $registrant;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        AddressBook::where('public_key', $this->registrant)->delete();
    }
    public function tags()
    {
        return ['DeleteAddressBookItem',$this->registrant];
    }
}
