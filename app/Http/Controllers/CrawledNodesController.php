<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;


use App\CrawledNode;

/**
 * @group Node crawler
 *
 * Endpoints for querying crawled Nodes
 */
class CrawledNodesController extends Controller
{
    /**
	 * Get all nodes
	 *
	 * Returns a list of all node-ips crawled by the API
     * @response [
     * "35.187.201.101",
     * "35.198.198.253",
     * "35.204.197.53",
     * "46.101.107.63",
     * "138.68.71.110",
     * "188.166.20.95",
     * "159.89.119.116",
     * "46.101.4.189",
     * "165.227.239.201",
     * "178.128.244.97"
     * ]
	 *
	 */
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
