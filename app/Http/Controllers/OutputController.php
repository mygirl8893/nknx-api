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

use DB;

/**
 * @group Outputs
 *
 * Endpoints for querying transaction-outputs
 */
class OutputController extends Controller
{
    /**
	 * Get all outputs
	 *
	 * Returns all outputs in simple pagination format starting with the latest one 
	 *
     * @queryParam latest Limits the results returned Example:8
     * @queryParam per_page Number of results per page Example:5
     * @queryParam txType Transaction type Example:
     * @queryParam address NKN wallet address Example:
     * 
	 */
    public function showAll(Request $request){
        $latest = $request->get('latest');

        //hardcap
        if(!$latest || $latest > 1000000){
            $latest = 1000000;
        };
    
        $txType = $request->get('txType');
        $address = $request->get('address');
        $paginate = $request->get('per_page',50);

        $outputs_query = output::query()->orderBy('id', 'desc');
        $outputs_query->when($latest, function ($q, $latest) { 
            return $q->limit($latest);
        });
        $outputs_query->when($txType, function ($q, $txType) { 
            return $q->whereHas('transaction', function($q) use ($txType){
                $q->where('txType', $txType);
            });
            return $q->where("txType","=",$txType);
        });
        $outputs_query->when($address, function ($q, $address) {        
                return $q->where('address', $address);
        });

        if($latest > $paginate){
            $outputs = $outputs_query
                ->simplePaginate($paginate);
        }
        else{
            $outputs = $outputs_query
                ->get();
        }
        return response()->json($outputs);
    }
    
}