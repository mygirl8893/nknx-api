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
     * @queryParam latest Limits the results returned
     * @queryParam paginate Number of results per page
     * @queryParam from Starting date in the format "YEAR-MONTH-DAY HOUR:MINUTE:SECOND"
     * @queryParam to End date in the format "YEAR-MONTH-DAY HOUR:MINUTE:SECOND"
     * @queryParam blockproposer Returns only blocks which are singed by the given public key
     * 
     * @response [
     *           {
     *               "id": 251340,
     *               "hash": "5efccab3866bfe1e75a3e1c767789868cc956083859e4aed65e2dd38b66bb791",
     *               "transaction_count": 4,
     *               "created_at": "2018-11-20 18:55:07",
     *               "updated_at": "2018-11-20 18:55:07",
     *               "header": {
     *                   "id": 251340,
     *                   "chordId": "",
     *                   "hash": "0000000000000000000000000000000000000000000000000000000000000000",
     *                   "height": 251340,
     *                   "prevBlockHash": "26f852d0b80ed21b8a9300f8897b85483495d29227541d65a2785c13c8444c27",
     *                   "signature": "b366ea88ddf82efd15aa9fd227e4779cde1a01639e13670991ed3078d4705e064c0b978a6648a04642efb08b2ac5198e4713f5bfe3ec687d4020ccc2f49db2ed",
     *                   "signer": "0391ec6b95b47c906d7e73a9180f6df66e54a322f13b96521043d426ed9dd7eaf9",
     *                   "timestamp": "2018-11-20 18:54:41",
     *                   "transactionsRoot": "0a193e59a6dd58a2fdc3a4580a8a8b362e78ca0ec1e271324f3ff9cbfe7d8b1d",
     *                   "version": 1,
     *                   "winningHash": "4ce4e815227583bfbaa2ca38152c54dad08e1894ec4a10ef95c3e63870be2e04",
     *                   "winningHashType": 1,
     *                   "block_id": 251340,
     *                   "created_at": "2018-11-20 18:55:07",
     *                   "updated_at": "2018-11-20 18:55:07"
     *               }
     *           },
     *           {
     *               "id": 251339,
     *               "hash": "26f852d0b80ed21b8a9300f8897b85483495d29227541d65a2785c13c8444c27",
     *               "transaction_count": 2,
     *               "created_at": "2018-11-20 18:55:06",
     *               "updated_at": "2018-11-20 18:55:06",
     *               "header": {
     *                   "id": 251339,
     *                   "chordId": "",
     *                   "hash": "0000000000000000000000000000000000000000000000000000000000000000",
     *                   "height": 251339,
     *                   "prevBlockHash": "bd75a5a6a62150a1372f7156ce016eee3c846d5a1aa6b14016a11eb55b214f21",
     *                   "signature": "0389ecc4c4463b25a63194982efa4a87dcb6aec26e88b830775a947183cf65b25224c1dde886bdf7dd3a9c9a1be792043bd2d9e8ab0d4e53eecbb43c2fafb5d8",
     *                   "signer": "02bac5f29266d66cc9f2c7931d4248f857d691e3066edbe6ffe5ad1781256b9c2a",
     *                   "timestamp": "2018-11-20 18:54:20",
     *                   "transactionsRoot": "7f01b0658e6acb5ba01a04cbd07794cf82cae478d2f4b7289adf756364c971d0",
     *                   "version": 1,
     *                   "winningHash": "9406fd2565caec0145d2866c779f197199fa8a820252b808d790f821ce83e23f",
     *                   "winningHashType": 1,
     *                   "block_id": 251339,
     *                   "created_at": "2018-11-20 18:55:06",
     *                   "updated_at": "2018-11-20 18:55:06"
     *               }
     *           }
     *     ]
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
     * @queryParam id required Limits the results returned
     * @queryParam withoutTransactions add block transactions to result
     * 
     * @response {
     *       "id": 212345,
     *       "hash": "4a78e2f807a02d54cdc271becf8f62013302ce823dd70531de5382bdbb997982",
     *       "transaction_count": 5,
     *       "created_at": "2018-11-09 23:10:07",
     *       "updated_at": "2018-11-09 23:10:07",
     *       "transactions": [
     *           {
     *               "id": 1068263,
     *               "hash": "0f4d16d2483371bab1e1a4e9a8f489058707e53c542679f33988249f8824cb30",
     *               "payloadVersion": 0,
     *               "txType": 0,
     *               "block_id": 212345,
     *               "sender": null,
     *               "created_at": "2018-11-09 23:10:07",
     *               "updated_at": "2018-11-09 23:10:07"
     *           },
     *           {
     *               "id": 1068264,
     *               "hash": "ae537ee400a7c7866bba6b562f864685fdcdf93378cd73d0d5be749de9330d97",
     *               "payloadVersion": 0,
     *               "txType": 66,
     *               "block_id": 212345,
     *               "sender": null,
     *               "created_at": "2018-11-09 23:10:07",
     *               "updated_at": "2018-11-09 23:10:07"
     *           },
     *           {
     *               "id": 1068265,
     *               "hash": "ded2a288cb356279814b8c985433bac67b10e0b9d345f6550a6e4d0fcc84c167",
     *               "payloadVersion": 0,
     *               "txType": 66,
     *               "block_id": 212345,
     *               "sender": null,
     *               "created_at": "2018-11-09 23:10:07",
     *               "updated_at": "2018-11-09 23:10:07"
     *           },
     *           {
     *               "id": 1068266,
     *               "hash": "29c8ef8a5b9aa329f956a46e69e79b1375a16b4db8b88fe4a7b70475d7d72546",
     *               "payloadVersion": 0,
     *               "txType": 66,
     *               "block_id": 212345,
     *               "sender": null,
     *               "created_at": "2018-11-09 23:10:07",
     *               "updated_at": "2018-11-09 23:10:07"
     *           },
     *           {
     *               "id": 1068267,
     *               "hash": "bc7f791822d59cd022eddcdd2e0e253c400e50fcd9f9534b2ccf5506ad2b1f08",
     *               "payloadVersion": 0,
     *               "txType": 66,
     *               "block_id": 212345,
     *               "sender": null,
     *               "created_at": "2018-11-09 23:10:07",
     *               "updated_at": "2018-11-09 23:10:07"
     *           }
     *       ],
     *       "header": {
     *           "id": 212345,
     *           "chordId": "",
     *           "hash": "0000000000000000000000000000000000000000000000000000000000000000",
     *           "height": 212345,
     *           "prevBlockHash": "da5ac4f1802256fef7c21b8d8907fb950e707849a4f74e0710af0d43cb3f7687",
     *           "signature": "dd4d1cde07245a89e05d0196b4762931c4ad1bcc99a7fb2d543a0832e994d7cd8f7f12d6d7d54b7c4e5553276fbb17fbb422a2e1fcb7ebcd44ecebfb57121d86",
     *           "signer": "03ec987bb6698154b769f0e37ef1e7ef75524a5dc83cffb08a88c2c1cc7a38d64f",
     *           "timestamp": "2018-11-09 23:09:23",
     *           "transactionsRoot": "8f30383e38d8f868d8c587d82cb715ce38eb65fc6e169e27223468ceea3a62c2",
     *           "version": 1,
     *           "winningHash": "ae537ee400a7c7866bba6b562f864685fdcdf93378cd73d0d5be749de9330d97",
     *           "winningHashType": 1,
     *           "block_id": 212345,
     *           "created_at": "2018-11-09 23:10:07",
     *           "updated_at": "2018-11-09 23:10:07",
     *           "nextBlockHash": "bb6f4772d206418f5e98e3b2f973dc0179f0014bf284031aacd49f5e1d8be257"
     *       }
     *   }
     * 
	 */
    public function show($id,Request $request)
    {
        $withoutTransactions = $request->get('withouttransactions',false);

        if(is_numeric($id)){
            $id = Header::where('height',$id)->pluck('block_id')->toArray();
            $block = Block::where('id',$id)
                ->when(!$withoutTransactions, function ($q) { 
                    return $q->with('transactions');
                })
                ->with('header')
                ->first();
        }
        else{
            
            $block = Block::where('hash',$id)
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
     * @queryParam id required Limits the results returned
     * @queryParam withoutpayload add transaction payload to result
     * 
     * @response [
     *           {
     *               "id": 1068263,
     *               "hash": "0f4d16d2483371bab1e1a4e9a8f489058707e53c542679f33988249f8824cb30",
     *               "payloadVersion": 0,
     *               "txType": 0,
     *               "block_id": 212345,
     *               "sender": null,
     *               "created_at": "2018-11-09 23:10:07",
     *               "updated_at": "2018-11-09 23:10:07"
     *           },
     *           {
     *               "id": 1068264,
     *               "hash": "ae537ee400a7c7866bba6b562f864685fdcdf93378cd73d0d5be749de9330d97",
     *               "payloadVersion": 0,
     *               "txType": 66,
     *               "block_id": 212345,
     *               "sender": null,
     *               "created_at": "2018-11-09 23:10:07",
     *               "updated_at": "2018-11-09 23:10:07"
     *           },
     *           {
     *               "id": 1068265,
     *               "hash": "ded2a288cb356279814b8c985433bac67b10e0b9d345f6550a6e4d0fcc84c167",
     *               "payloadVersion": 0,
     *               "txType": 66,
     *               "block_id": 212345,
     *               "sender": null,
     *               "created_at": "2018-11-09 23:10:07",
     *               "updated_at": "2018-11-09 23:10:07"
     *           },
     *           {
     *               "id": 1068266,
     *               "hash": "29c8ef8a5b9aa329f956a46e69e79b1375a16b4db8b88fe4a7b70475d7d72546",
     *               "payloadVersion": 0,
     *               "txType": 66,
     *               "block_id": 212345,
     *               "sender": null,
     *               "created_at": "2018-11-09 23:10:07",
     *               "updated_at": "2018-11-09 23:10:07"
     *           },
     *           {
     *               "id": 1068267,
     *               "hash": "bc7f791822d59cd022eddcdd2e0e253c400e50fcd9f9534b2ccf5506ad2b1f08",
     *               "payloadVersion": 0,
     *               "txType": 66,
     *               "block_id": 212345,
     *               "sender": null,
     *               "created_at": "2018-11-09 23:10:07",
     *               "updated_at": "2018-11-09 23:10:07"
     *           }
     *       ]
	 */   
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
