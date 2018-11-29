<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client as GuzzleHttpClient;
use GuzzleHttp\Exception\RequestException;

use App\Block;
use App\Header;
use App\Transaction;
use App\Program;
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
     *      "registrant": "02ac9d242fad2ac6e231c4ecda82b1b108c12b1e21a685053a41d64f9886f6fdea"
     *  },
     *  {
     *      "name": "elonmusk",
     *      "registrant": "035e5cce1fa0049684b6e1438d3398382dcca298f4e0363109a90a3d7039009bc7"
     *  },
     *  {
     *      "name": "trueinsider",
     *      "registrant": "039bd29d184aa8acc963a46c083129737c0a78615b86656463b3f1e5e06df9a1a3"
     *  },
     *  {
     *      "name": "bittorrent",
     *      "registrant": "027865c7c961e7d5d3f96920803f4d6a0e4e48cca4f98fd23b370799c2598053b5"
     *  }
     * ]
     */
    public function showWalletNames(){
        $walletNames= AddressBook::all();
        return response()->json($walletNames);
    }
}