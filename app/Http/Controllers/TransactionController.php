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

    public function show($id,Request $request)
    {

        $withoutPayload = $request->get('withoutpayload',false);
        $withoutOutputs = $request->get('withoutoutputs',false);
        $withoutInputs = $request->get('withoutinputs',false);
        $withoutAttributes = $request->get('withoutattributes',false);
        


        $transactions_query = Transaction::query()->where('hash',$id);
        
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
            ->get();
        
        return response()->json($transactions); 
    }

    public function showWalletNames(){

        $response = array();

        $createdWalletNames = Transaction::where('txType',80)
            ->with('payload')
            ->orderBy('id', 'asc')
            ->get()
            ->toArray();
        $deletedWalletNames = Transaction::where('txType',82)
            ->with('payload')
            ->orderBy('id', 'asc')
            ->get()
            ->toArray();

        foreach($deletedWalletNames as $deletedWalletName){
            $i = 0;
            while( $i<count($createdWalletNames)){
                if($createdWalletNames[$i]["payload"]["registrant"] == $deletedWalletName["payload"]["registrant"]){
                    array_splice($createdWalletNames,$i,1);
                    break;
                }
                $i++;
            }
        }

        foreach($createdWalletNames as $createdWalletName){
            $responseItem = [
                "name" => $createdWalletName["payload"]["name"],
                "registrant" => $createdWalletName["payload"]["registrant"]
            ];
            array_push($response,$responseItem);
        }


       // return response()->json($deletedWalletNames); 
        return response()->json($response); 

    }
}