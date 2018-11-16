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

class StatisticController extends Controller
{
    public function blocks_daily(Request $request){
        $pubkey = $request->get('pubkey',false);

        $headers_query = Header::select(DB::raw("COUNT(*) as count, DATE(timestamp) AS date"))
        ->when($pubkey, function ($q, $pubkey) { 
            return $q->where('signer',$pubkey);
        })
        ->orderBy(DB::raw('DATE(timestamp)'), 'desc')
        ->groupBy(DB::raw('DATE(timestamp)'));
        $headers = $headers_query
                ->get();
        return response()->json($headers);
    }

    public function transactions_daily(Request $request){
        $transactions_query = Transaction::select(DB::raw("COUNT(*) as count, DATE(created_at) AS date"))
        ->orderBy(DB::raw('DATE(created_at)'), 'desc')
        ->groupBy(DB::raw('DATE(created_at)'));
        $transactions = $transactions_query
                ->get();
        return response()->json($transactions);
    }

    public function transfers_daily(Request $request){
        $transfers_query = Transaction::select(DB::raw("COUNT(*) as count, DATE(created_at) AS date"))
        ->where('txType',16)
        ->orderBy(DB::raw('DATE(created_at)'), 'desc')
        ->groupBy(DB::raw('DATE(created_at)'));
        $transfers = $transfers_query
                ->get();
        return response()->json($transfers);
    }


}