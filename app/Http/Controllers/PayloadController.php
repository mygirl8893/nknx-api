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
    /**
	 * Get single payload
	 *
	 * Returns a specific payload based on a transaction-database-id
	 *
     * @queryParam tId required The database-id of the according transaction
     * 
	 */
    public function show($tId)
    {
        $payload = Payload::where('transaction_id',$tId)->get();
        return response()->json($payload); 
    }
}