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

class BlockController extends Controller
{
    public function showAll(Request $request){
        $latest = $request->get('latest');
        if( $latest > 1000){
            $latest = 1000;
        }

        $paginate = $request->get('per_page',25);
        $from = date($request->get('from', false));
        $to = date($request->get('to', false));
        $blockproposer = $request->get('blockproposer', false);
        if(!$latest && !$from && !$to && !$blockproposer){
                    $block = Block::orderBy('id', 'desc')
                        ->limit(1000000)
                        ->with(['header'])
                        ->withCount('transactions')
                        ->simplePaginate($paginate);
            return response()->json($block);
        }
        else{
            $header_query = Header::query()->orderBy("height", "desc");
            
            $header_query->when($from && $to, function ($q) use ($from, $to) { 
                return $q->whereBetween('timestamp', [$from, $to]);
            });
            $header_query->when($from && !$to, function ($q) use ($from) { 
                return $q->where('timestamp', '>', $from);
            });
            $header_query->when(!$from && $to, function ($q) use ($to) { 
                return $q->where('timestamp', '<', $to);
            });
            $header_query->when($blockproposer, function ($q) use ($blockproposer) { 
                
                return $q->where('signer',"=",$blockproposer);
            });
            $header_query->when($latest, function ($q, $latest) { 
                return $q->limit($latest);
            });
            //dd($header_query->toSql());
            $ids = $header_query->pluck('block_id')->toArray();
            $ids_count = count($ids);
            $ids_ordered = implode(',', $ids);
            if($ids_count > $paginate){

                    $block = Block::whereIn('id',$ids)
                        ->orderBy('id', 'desc')
                        ->with(['header'])
                        ->withCount('transactions')
                        ->simplePaginate($paginate);      
            }
            else{
 
                    $block = Block::whereIn('id',$ids)
                        ->orderBy('id', 'desc')
                        ->with(['header'])
                        ->withCount('transactions')
                        ->get();
 
            }
            return response()->json($block);
        }
    }

    public function show($id)
    {
        if(is_numeric($id)){
            $id = Header::where('height',$id)->pluck('block_id')->toArray();
            $block = Block::where('id',$id)
                ->with(['header','transactions'])
                ->get();
        }
        else{
            $block = Block::where('hash',$id)
            ->with(['header','transactions'])
            ->get();
        }
        return response()->json($block); 
    }
    public function showBlockTransactions($id,Request $request){
        $withoutPayload = $request->get('withoutpayload',false);
        if(is_numeric($id)){
            $id = Header::where('height',$id)->pluck('block_id')->toArray();
            $block = Block::where('id',$id)
                ->first();
        }
        else{
            $block = Block::where('hash',$id)
                ->first();
        }
        if($withoutPayload){
            $transactions = $block
                ->transactions()
                ->with(['outputs','attributes'])
                ->get();
        }
        else {
            $transactions = $block
                ->transactions()
                ->with(['payload','outputs','attributes'])
                ->get();
        }
        return response()->json($transactions); 

    }
}
