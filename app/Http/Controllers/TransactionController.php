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
        $filter = $request->get('filter');
        
        $paginate = $request->get('per_page');
        if(!$paginate){
            $paginate = 25;
        }
        if ($filter){

            if($filter > $paginate){
                $transaction = Transaction::orderBy('id', 'desc')
                    ->limit($filter)
                    ->with(['attribute','output','payload'])
                    ->paginate($paginate);
            }
            else{
                $transaction = Transaction::orderBy('id', 'desc')
                ->limit($filter)
                ->with(['attribute','output','payload'])
                ->get();
            }
        }
        else{
            $transaction = Transaction::orderBy('id', 'desc')
                ->with(['attribute','output','payload'])
                ->get();
        }
       
        return response()->json($block);
    }
    public function show_flat($id){
        
    

    }

    public function show($id)
    {
        
        
    }
}
