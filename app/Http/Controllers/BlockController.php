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
            $ids = Header::limit($filter)->orderBy('height','desc')->pluck('block_id')->toArray();
            $ids_count = count($ids);
            $ids_ordered = implode(',', $ids);
            if($ids_count > $paginate){
                $block = Block::whereIn('id',$ids)
                    ->orderBy('id', 'desc')
                    ->with(['header','transactions'])
                    ->paginate($paginate);
            }
            else{
                $block = Block::whereIn('id',$ids)
                    ->orderBy('id', 'desc')
                    ->with(['header','transactions'])
                    ->get();
            }
        }
        else{
            $block = Block::with(['header','transactions'])
                ->orderBy('id', 'desc')
                ->paginate($paginate);
        }
       
        return response()->json($block);
    }
    public function show_flat($id){
        if(is_numeric($id)){
            $id = Header::where('height',$id)->pluck('block_id')->toArray();
            $block = Block::whereIn('blocks.id',$id)
                ->join(
                    'headers',
                    'blocks.id','=','headers.block_id'
                    )
                ->select(['blocks.hash','headers.height','headers.prevBlockHash','headers.signature','headers.signer','headers.timestamp','headers.transactionsRoot','headers.version','headers.winningHash','headers.winningHashType'])
                ->get();
        }
        else{
            $block = Block::where('blocks.hash',$id)
                ->join(
                    'headers',
                    'blocks.id','=','headers.block_id'
                    )
                ->select(['blocks.hash','headers.height','headers.prevBlockHash','headers.signature','headers.signer','headers.timestamp','headers.transactionsRoot','headers.version','headers.winningHash','headers.winningHashType'])
                ->get();
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
