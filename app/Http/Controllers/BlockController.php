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
        $paginate = $request->get('per_page',25);
        $from = $request->get('from', false);
        $to = $request->get('to', false);
        $blockproposer = $request->get('blockproposer', false);
        //flat returns the block model without transactions in one object
        $flat = $request->get('flat',false);
        //dd($blockproposer);


        $header_query = Header::query();
        
        $header_query->when($from && $to, function ($q, $from, $to) { 
            return $q->whereBetween('timestamp', [$from, $to]);
        });
        $header_query->when($from && !$to, function ($q, $from) { 
            return $q->where('timestamp', ">", $from);
        });
        $header_query->when(!$from && $to, function ($q, $to) { 
            return $q->where('timestamp', "<", $to);
        });
        $header_query->when($blockproposer, function ($q, $blockproposer) { 
            
            return $q->where('signer',"=",$blockproposer);
        });
        $header_query->when($latest, function ($q, $latest) { 
            return $q->limit($latest);
        });


        $ids = $header_query->pluck('block_id')->toArray();
        

        $ids_count = count($ids);
        $ids_ordered = implode(',', $ids);
        if($ids_count > $paginate){
            if($flat){
                $block = Block::whereIn('blocks.id',$ids)
                ->orderBy('blocks.id', 'desc')
                ->join(
                    'headers',
                    'blocks.id','=','headers.block_id'
                    )
                ->select(['blocks.hash','headers.height','headers.prevBlockHash','headers.signature','headers.signer','headers.timestamp','headers.transactionsRoot','headers.version','headers.winningHash','headers.winningHashType'])
                ->paginate($paginate);
            }
            else{
                $block = Block::whereIn('id',$ids)
                    ->orderBy('id', 'desc')
                    ->with(['header','transactions'])
                    ->paginate($paginate);
            }
        }
        else{
            if($flat){
                $block = Block::whereIn('blocks.id',$ids)
                    ->orderBy('blocks.id', 'desc')
                    ->join(
                        'headers',
                        'blocks.id','=','headers.block_id'
                        )
                    ->select(['blocks.hash','headers.height','headers.prevBlockHash','headers.signature','headers.signer','headers.timestamp','headers.transactionsRoot','headers.version','headers.winningHash','headers.winningHashType'])
                    ->get();
            }
            else{
                $block = Block::whereIn('id',$ids)
                    ->orderBy('id', 'desc')
                    ->with(['header','transactions'])
                    ->get();
            }
        }
        

        return response()->json($block);
    }

    public function show($id)
    {
        if(is_numeric($id)){
            $id = Header::where('height',$id)->pluck('block_id')->toArray();
            $block = Block::whereIn('id',$id)
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
}
