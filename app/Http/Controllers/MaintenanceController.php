<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client as GuzzleHttpClient;
use GuzzleHttp\Exception\RequestException;

use App\Block;
use App\Jobs\FillChordID;
use DB;
use Carbon\Carbon;


class MaintenanceController extends Controller
{

    public function fillChordID(){
        $blocks = Block::whereNull('chordID')->limit(100000)->get();
        foreach ($blocks as $block) {
            FillChordID::dispatch($block);
        }

        //FillChordID::dispatch($id);
    }

    public function test(){
        $id="be522453fae3cfe25efa14521d94f464e41a48147675dae86efded4a9fba9286";

        $blocks_query = Block::select(DB::raw("COUNT(*) as count, EXTRACT(WEEK from timestamp) AS week"))
                ->where('chordID',$id)
                ->orderBy(DB::raw('EXTRACT(WEEK from timestamp)'), 'desc')
                ->groupBy(DB::raw('EXTRACT(WEEK from timestamp)'))
                ->where('timestamp', '>=', Carbon::now()->subWeeks(1));
        $stats = $blocks_query
                        ->first();
        return response()->json($stats->count);
    }

}
