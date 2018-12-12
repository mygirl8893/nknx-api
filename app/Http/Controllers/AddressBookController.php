<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client as GuzzleHttpClient;
use GuzzleHttp\Exception\RequestException;

use App\Block;
use App\Transaction;
use App\Attribute;
use App\Output;
use App\Payload;
use App\AddressBook;

use DB;

/**
 * @group Address book
 *
 * Endpoints for querying registered names in the NKN blockchain
 */
class AddressBookController extends Controller
{
    /**
     * Get all registered wallet names
     *
     * Returns all registered wallet names and the registrants public key
     * 
     * @response [
     *  {
     *      "name": "microsoft",
     *      "public_key": "02ac9d242fad2ac6e231c4ecda82b1b108c12b1e21a685053a41d64f9886f6fdea",
     *      "address":"NgugCfHpsYV2zMDwHwEqm147VsW1akrSyL"
     *  },
     *  {
     *      "name": "elonmusk",
     *      "public_key": "035e5cce1fa0049684b6e1438d3398382dcca298f4e0363109a90a3d7039009bc7",
     *      "address":"Nd4DfvaB95YAQzVAPtFc59Ap8YpTq9urBo"
     *  },
     *  {
     *      "name": "trueinsider",
     *      "public_key": "039bd29d184aa8acc963a46c083129737c0a78615b86656463b3f1e5e06df9a1a3",
     *      "address":"NTs3B7YsM6aRDCSMzqTdgbdkW5Q6YBBUZn"
     *  },
     *  {
     *      "name": "bittorrent",
     *      "public_key": "027865c7c961e7d5d3f96920803f4d6a0e4e48cca4f98fd23b370799c2598053b5",
     *      "address":"NQ7KwEzta2CJz2MCFzq2xf8jyH8UUE7PpU"
     *  }
     * ]
     * 
     * 
     */
    public function showWalletNames(){
        $walletNames= AddressBook::all();
        return response()->json($walletNames);
    }

    /**
     * Get name by address
     *
     * Returns the name of a specific address
     * 
     * @response 
     *  "christnknx"
     * 
     * 
     * 
     */
    public function getNameByAddress($address){
        return response()->json(AddressBook::where('address',$address)->first());
    }
}