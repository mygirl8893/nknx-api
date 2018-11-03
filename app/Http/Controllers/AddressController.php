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

use DB;

class AddressController extends Controller
{
    public function showAll(Request $request){

        $outputs= output::query()
            ->selectRaw('address, max(created_at) as last_transaction, count(address) as transactions,min(created_at) as first_transaction')
            ->orderBy('last_transaction', 'desc')
            ->groupBy('address')
            ->simplePaginate(50);
        return response()->json($outputs);
    }

}