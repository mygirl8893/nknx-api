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
 * @group Outputs
 *
 * Endpoints for querying transaction-outputs
 */
class OutputController extends Controller
{
    public function showAll(Request $request){
        $by_miner = $request->get('by_miner',false);
        $latest = $request->get('latest');
        $txType = $request->get('txType');
        $address = $request->get('address');
        $paginate = $request->get('per_page',50);

        if($by_miner){
            $outputs_query = output::select("address",DB::raw('count(*) as total'))
                ->whereHas('transaction', function($q) use ($txType){
                    $q->where('txType', 0);
                })
                ->groupBy('address')
                ->orderBy('total','desc');
            $outputs_query->when($latest, function ($q, $latest) { 
                return $q->limit($latest);
            });
            $outputs = $outputs_query
                    ->get();
            return response()->json($outputs);
        }
        else{

            //hardcap
            if(!$latest || $latest > 1000000){
                $latest = 1000000;
            };
        
            $paginate = $request->get('per_page',50);
            $withoutPayload = $request->get('withoutpayload',false);
    
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
}