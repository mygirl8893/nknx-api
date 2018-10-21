<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;


use App\CrawledNode;


class CrawledNodesController extends Controller
{
    public function showAll(){
        $nodes = CrawledNode::all()->pluck('ip');
        return response()->json($nodes);
    }

    

}
