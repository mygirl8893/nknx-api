<?php

namespace App\Http\Controllers;

use App\Payload;

/**
 * @group Payloads
 *
 * Endpoints for querying transaction-payloads
 */
class PayloadController extends Controller
{
    public function show($tId)
    {
        $payload = Payload::where('transaction_id',$tId)->get();
        return response()->json($payload); 
    }
}