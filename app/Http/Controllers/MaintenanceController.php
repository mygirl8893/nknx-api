<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client as GuzzleHttpClient;
use GuzzleHttp\Exception\RequestException;

use App\Block;
use App\Jobs\FillChordID;


class MaintenanceController extends Controller
{

    public function fillChordID(){
        $blocks = Block::whereNull('chordID')->limit(100000)->get();
        foreach ($blocks as $block) {
            FillChordID::dispatch($block);
        }

        //FillChordID::dispatch($id);
    }


}
