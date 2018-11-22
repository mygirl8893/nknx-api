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
 * @group Blocks
 *
 * Endpoints for block-based queries
 */
class BlockController extends Controller
{
    /**
	 * Get all blocks
	 *
	 * Returns all blocks with corresponding headers in simple pagination format starting with the latest one 
	 *
     * @queryParam latest Limits the results returned Example:7
     * @queryParam per_page Number of results per page Example:4
     * @queryParam from Starting date in the format "YEAR-MONTH-DAY HOUR:MINUTE:SECOND" Example:
     * @queryParam to End date in the format "YEAR-MONTH-DAY HOUR:MINUTE:SECOND" Example:
     * @queryParam blockproposer Returns only blocks which are singed by the given public key Example:
     * 
	 */
    public function showAll(Request $request){

        $latest = $request->get('latest');
        if( $latest > 1000){
            $latest = 1000;
        }

        $paginate = $request->get('per_page',50);
        $from = date($request->get('from', false));
        $to = date($request->get('to', false));
        $blockproposer = $request->get('blockproposer', false);
        if(!$latest && !$from && !$to && !$blockproposer){
            $block = Block::orderBy('id', 'desc')
                ->limit(1000000)
                ->with(['header'])
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

            $ids = $header_query->pluck('block_id')->toArray();
            $ids_count = count($ids);
            $ids_ordered = implode(',', $ids);
            if($ids_count > $paginate){

                    $block = Block::whereIn('id',$ids)
                        ->orderBy('id', 'desc')
                        ->with(['header'])
                        ->simplePaginate($paginate);      
            }
            else{
 
                    $block = Block::whereIn('id',$ids)
                        ->orderBy('id', 'desc')
                        ->with(['header'])
                        ->get();
 
            }
            return response()->json($block);
        }
    }

    /**
	 * Get single block by height/hash
	 *
	 * Returns a specific block based on the height or block hash 
	 *
     * @queryParam block_id required Id of the resource
     * @queryParam withoutTransactions add block transactions to result
     * 
	 */
    public function show($block_id,Request $request)
    {
        $withoutTransactions = $request->get('withouttransactions',false);

        if(is_numeric($block_id)){
            $id = Header::where('height',$block_id)->pluck('block_id')->toArray();
            $block = Block::where('id',$id)
                ->when(!$withoutTransactions, function ($q) { 
                    return $q->with('transactions');
                })
                ->with('header')
                ->first();
        }
        else{
            
            $block = Block::where('hash',$block_id)
                ->when(!$withoutTransactions, function ($q) { 
                    return $q->with('transactions');
                })
                ->with('header')
                ->first();
        }
        
        $nextBlock = Header::where('prevBlockHash',$block->hash)->with('block')->get();
        if(count($nextBlock)){
            $nextBlock = $nextBlock[0]->block->hash;
            $block->header->nextBlockHash = $nextBlock;
        }
        
        return response()->json($block); 
    }
     /**
	 * Get transactions
	 *
	 * Returns all transactions of a specific block based on the height or block hash 
	 *
     * @queryParam block_id required Limits the results returned
     * @queryParam withoutpayload add transaction payload to result
     * 
	 */   
    public function showBlockTransactions($block_id,Request $request){
        $withoutPayload = $request->get('withoutpayload',false);
        if(is_numeric($block_id)){
            $id = Header::where('height',$block_id)->pluck('block_id')->toArray();
            $block = Block::where('id',$block_id)
                ->first();
        }
        else{
            $block = Block::where('hash',$block_id)
                ->first();
        }
        if($withoutPayload){
            $transactions = $block
                ->transactions()
                ->with(['outputs','attributes','block.header'])
                ->get();
        }
        else {
            $transactions = $block
                ->transactions()
                ->with(['payload','outputs','attributes','block.header'])
                ->get();
        }
        return response()->json($transactions); 

    }

}
