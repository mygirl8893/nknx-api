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

use Carbon\Carbon;

use DB;

/**
 * @group Statistics
 *
 * Endpoints for NKN Network statistics
 */

class StatisticController extends Controller
{
    /**
	 * Daily blocks
	 *
	 * Shows the amount of blocks generated per day
     * @queryParam latest Limits the results returned Example:8
     * @queryParam pubkey Only return blocks that were signed by the given public key Example:
     * 
     */ 
    public function blocks_daily(Request $request){
        $pubkey = $request->get('pubkey',false);
        $latest = $request->get('latest');

        if(!$latest || $latest > 31){
            $latest = 31;
        };

        $blocks_query = Block::select(DB::raw("COUNT(*) as count, DATE(timestamp) AS date"))
        ->when($pubkey, function ($q, $pubkey) { 
            return $q->where('signer',$pubkey);
        })
        ->orderBy(DB::raw('DATE(timestamp)'), 'desc')
        ->groupBy(DB::raw('DATE(timestamp)'))
        ->when($latest, function ($q, $latest) { 
            return $q->limit($latest);
        });
        $blocks = $blocks_query
                ->get();
        return response()->json($blocks);
    }

    /**
	 * Daily transactions
	 *
	 * Shows the amount of transactions generated per day
     * @queryParam latest Limits the results returned Example:8
     * 
     */ 
    public function transactions_daily(Request $request){
        $latest = $request->get('latest');
        if(!$latest || $latest > 31){
            $latest = 31;
        };

        $transactions_query = Transaction::select(DB::raw("COUNT(*) as count, DATE(timestamp) AS date"))
        ->orderBy(DB::raw('DATE(timestamp)'), 'desc')
        ->groupBy(DB::raw('DATE(timestamp)'))
        ->when($latest, function ($q, $latest) { 
            return $q->limit($latest);
        });
        $transactions = $transactions_query
                ->get();
        return response()->json($transactions);
    }

    /**
	 * Daily transfers
	 *
	 * Shows the amount of transfers generated per day
     * @queryParam latest Limits the results returned Example:8
     * 
     */ 
    public function transfers_daily(Request $request){
        $latest = $request->get('latest');
        if(!$latest || $latest > 31){
            $latest = 31;
        };
        $transfers_query = Transaction::select(DB::raw("COUNT(*) as count, DATE(timestamp) AS date"))
        ->where('txType',16)
        ->orderBy(DB::raw('DATE(timestamp)'), 'desc')
        ->groupBy(DB::raw('DATE(timestamp)'))
        ->when($latest, function ($q, $latest) { 
            return $q->limit($latest);
        });
        $transfers = $transfers_query
                ->get();
        return response()->json($transfers);
    }

    /**
	 * Monthly blocks
	 *
	 * Shows the amount of blocks generated per month
     * @queryParam latest Limits the results returned Example:8
     * @queryParam pubkey Only return blocks that were signed by the given public key Example:
     * 
     */ 
    public function blocks_monthly(Request $request){
        $pubkey = $request->get('pubkey',false);
        $latest = $request->get('latest');

        if(!$latest || $latest > 12){
            $latest = 12;
        };

        $blocks_query = Block::select(DB::raw("COUNT(*) as count, MONTH(timestamp) AS month"))
        ->when($pubkey, function ($q, $pubkey) { 
            return $q->where('signer',$pubkey);
        })
        ->orderBy(DB::raw('MONTH(timestamp)'), 'desc')
        ->groupBy(DB::raw('MONTH(timestamp)'))
        ->when($latest, function ($q, $latest) { 
            return $q->where('timestamp', '>=', Carbon::now()->subMonths($latest));
        });
        $blocks = $blocks_query
                ->get();
        return response()->json($blocks);
    }

    /**
	 * Monthly transactions
	 *
	 * Shows the amount of transactions generated per day
     * @queryParam latest Limits the results returned Example:8
     * 
     */ 
    public function transactions_monthly(Request $request){
        $latest = $request->get('latest');
        if(!$latest || $latest > 12){
            $latest = 12;
        };

        $transactions_query = Transaction::select(DB::raw("COUNT(*) as count, MONTH(timestamp) AS month"))
        ->orderBy(DB::raw('MONTH(timestamp)'), 'desc')
        ->groupBy(DB::raw('MONTH(timestamp)'))
        ->when($latest, function ($q, $latest) { 
            return $q->where('timestamp', '>=', Carbon::now()->subMonths($latest));
        });
        $transactions = $transactions_query
                ->get();
        return response()->json($transactions);
    }

    /**
	 * Monthly transfers
	 *
	 * Shows the amount of transfers generated per day
     * @queryParam latest Limits the results returned Example:8
     * 
     */ 
    public function transfers_monthly(Request $request){
        $latest = $request->get('latest');
        if(!$latest || $latest > 12){
            $latest = 12;
        };
        $transfers_query = Transaction::select(DB::raw("COUNT(*) as count, MONTH(timestamp) AS month"))
        ->where('txType',16)
        ->orderBy(DB::raw('MONTH(timestamp)'), 'desc')
        ->groupBy(DB::raw('MONTH(timestamp)'))
        ->when($latest, function ($q, $latest) { 
            return $q->where('timestamp', '>=', Carbon::now()->subMonths($latest));
        });
        $transfers = $transfers_query
                ->get();
        return response()->json($transfers);
    }

    /**
	 * All miners
	 *
	 * Shows the amount of mined blocks by all wallet addresses
     * @queryParam latest Limits the results returned Example:8
     * 
     */ 
    public function miners_overall(Request $request){
        $latest = $request->get('latest');
        $outputs = Output::select("address",DB::raw('count(*) as total'))
        ->whereHas('transaction', function($q){
            $q->where('txType', 0);
        })
        ->groupBy('address')
        ->orderBy('total','desc')
        ->when($latest, function ($q, $latest) { 
            return $q->limit($latest);
        })
        ->get();
        return response()->json($outputs);
    }


}