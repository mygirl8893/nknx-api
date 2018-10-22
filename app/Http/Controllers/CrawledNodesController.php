<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;


use App\CrawledNode;


class CrawledNodesController extends Controller
{
    public function showAll(Request $request){
        $withLocation = $request->get('withLocation', false);
        if($withLocation){
            $nodes = CrawledNode::all();
        }
        else{
            $nodes = CrawledNode::all()->pluck('ip');
        }
       
        return response()->json($nodes);
    }

    

}
