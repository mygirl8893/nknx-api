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

class TransactionController extends Controller
{
    /**
     * Show the profile for the given user.
     *
     * @param  int  $id
     * @return Response
     */
    public function showAll(Request $request){
        $latest = $request->get('latest');
        //hardcap
        if(!$latest || $latest > 1000000){
            $latest = 1000000;
        };
       
        $paginate = $request->get('per_page',25);
        $withoutPayload = $request->get('withoutpayload',false);
 
        $transactions_query = Transaction::query()->orderBy('id', 'desc');
        $transactions_query->when($latest, function ($q, $latest) { 
            return $q->limit($latest);
        });


        if($latest > $paginate){
            if($withoutPayload){
                $transactions_query
                    ->with(['attributes','outputs','block.header']);
            }
            else{
                $transactions_query
                    ->with(['attributes','outputs','payload','block.header']);
            }
            $transactions = $transactions_query
                ->simplePaginate($paginate);
        }
        else{
 
            if($withoutPayload){
                $transactions_query
                    ->with(['attributes','outputs','block.header']);
            }
            else{
                $transactions_query
                    ->with(['attributes','outputs','payload','block.header']);
            }

            $transactions = $transactions_query
                ->get();
        }
        return response()->json($transactions);
    }
}