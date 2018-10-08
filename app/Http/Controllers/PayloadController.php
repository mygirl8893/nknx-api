<?php

namespace App\Http\Controllers;

use App\Payload;

class PayloadController extends Controller
{
    public function show($tId)
    {
        $payload = Payload::where('transaction_id',$tId)->get();
        return response()->json($payload); 
    }
}