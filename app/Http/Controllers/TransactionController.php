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

/**
 * @group Transactions
 *
 * Endpoints for querying transactions
 */
class TransactionController extends Controller
{
    /**
	 * Get all transactions
	 *
	 * Returns all transactions with corresponding headers, payloads, outputs, inputs and attributes in simple pagination format starting with the latest one 
	 *
     * @queryParam latest Limits the results returned Example:7
     * @queryParam per_page Number of results per page Example:4
     * @queryParam txType Filter results by txType - single or comma separated Example:
     * @queryParam address Filter results by NKN address Example:
     * @queryParam withoutPayload remove payload Example:true
     * @queryParam withoutOutputs remove outputs Example:true
     * @queryParam withoutInputs remove inputs Example:true
     * @queryParam withoutAttributes remove attributes Example:true
     * 
	 */
    public function showAll(Request $request){
        $latest = $request->get('latest');
        $txType = $request->get('txType');
        $txType = explode(',', $txType);
        $address = $request->get('address');
 

        //hardcap
        if(!$latest || $latest > 1000000){
            $latest = 1000000;
        };
       
        $paginate = $request->get('per_page',50);
        $withoutPayload = $request->get('withoutpayload',false);
        $withoutOutputs = $request->get('withoutoutputs',false);
        $withoutInputs = $request->get('withoutinputs',false);
        $withoutAttributes = $request->get('withoutattributes',false);
 
        $transactions_query = Transaction::query()->orderBy('id', 'desc');
        $transactions_query->when($latest, function ($q, $latest) { 
            return $q->limit($latest);
        });
        $transactions_query->when($txType, function ($q, $txType) { 
            return $q->whereIn("txType",$txType);
        });
        $transactions_query->when($address, function ($q, $address) { 
            return $q->whereHas('outputs', function($q) use ($address){
                $q->where('address', $address);
            })->orWhere('sender', $address);
        });
        $transactions_query->when(!$withoutPayload, function ($q) { 
            return $q->with('payload');
        });
        $transactions_query->when(!$withoutOutputs, function ($q) { 
            return $q->with('outputs');
        });
        $transactions_query->when(!$withoutInputs, function ($q) { 
            return $q->with('inputs');
        });
        $transactions_query->when(!$withoutAttributes, function ($q) { 
            return $q->with('attributes');
        });

        $transactions_query->with('block.header');

        if($latest > $paginate){
            $transactions = $transactions_query
                ->simplePaginate($paginate);
        }
        else{
            $transactions = $transactions_query
                ->get();
        }
        return response()->json($transactions);
    }

    /**
	 * Get single transaction by hash
	 *
	 * Returns a specific block based on the height or block hash 
	 *
     * @queryParam block_id required Id of the resource
     * @queryParam withoutPayload remove payload Example:false
     * @queryParam withoutOutputs remove outputs Example:false
     * @queryParam withoutInputs remove inputs Example:false
     * @queryParam withoutAttributes remove attributes Example:false
     * 
	 */
    public function show($tHash,Request $request)
    {

        $withoutPayload = $request->get('withoutpayload',false);
        $withoutOutputs = $request->get('withoutoutputs',false);
        $withoutInputs = $request->get('withoutinputs',false);
        $withoutAttributes = $request->get('withoutattributes',false);
        


        $transactions_query = Transaction::query()->where('hash',$tHash);
        
        $transactions_query->when(!$withoutPayload, function ($q) { 
            return $q->with('payload');
        });
        $transactions_query->when(!$withoutOutputs, function ($q) { 
            return $q->with('outputs');
        });
        $transactions_query->when(!$withoutInputs, function ($q) { 
            return $q->with('inputs');
        });
        $transactions_query->when(!$withoutAttributes, function ($q) { 
            return $q->with('attributes');
        });

        $transactions_query->with('block.header');
        $transactions = $transactions_query
            ->first();
        
        return response()->json($transactions); 
    }
}