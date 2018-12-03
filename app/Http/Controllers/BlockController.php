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


        $paginate = $request->get('per_page',50);
        $from = date($request->get('from', false));
        $to = date($request->get('to', false));
        $blockproposer = $request->get('blockproposer', false);

        $blocksQuery = Block::orderBy('height', 'desc')
            ->when($blockproposer, function ($q) use ($blockproposer) { 
                return $q->where('signer',"=",$blockproposer);
            })
            ->when($from && $to, function ($q) use ($from, $to) { 
                return $q->whereBetween('timestamp', [$from, $to]);
            })
            ->when($from && !$to, function ($q) use ($from) { 
                return $q->where('timestamp', '>', $from);
            })
            ->when(!$from && $to, function ($q) use ($to) { 
                return $q->where('timestamp', '<', $to);
            })
            ->when($latest, function ($q) use ($latest) { 
                return $q->limit($latest);
            });

        if (!$latest | $latest>$paginate){
            $blocks = $blocksQuery->simplePaginate($paginate);
        }
        else{
            $blocks = $blocksQuery->get();
        }
            
        return response()->json($blocks);
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
            $block = Block::where('height',$block_id)
                ->when(!$withoutTransactions, function ($q) { 
                    return $q->with('transactions');
                })
                ->first();
        }
        else{
            
            $block = Block::where('hash',$block_id)
                ->when(!$withoutTransactions, function ($q) { 
                    return $q->with('transactions');
                })
                ->first();
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
            $block = Block::where('height',$block_id)
                ->first();
        }
        else{
            $block = Block::where('hash',$block_id)
                ->first();
        }
        if($withoutPayload){
            $transactions = $block
                ->transactions()
                ->with(['outputs','attributes','block'])
                ->get();
        }
        else {
            $transactions = $block
                ->transactions()
                ->with(['payload','outputs','attributes','block'])
                ->get();
        }
        return response()->json($transactions); 

    }

}
