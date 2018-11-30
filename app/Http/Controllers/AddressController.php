<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client as GuzzleHttpClient;
use GuzzleHttp\Exception\RequestException;

use App\Block;
use App\Transaction;
use App\Attribute;
use App\Output;
use App\Payload;

use DB;

/**
 * @group Addresses
 *
 * Endpoints for address-based queries
 */
class AddressController extends Controller
{
    /**
	 * Get all addresses
	 *
	 * Returns all addresses in simple pagination format starting with the last active one 
	 *
     * @queryParam latest Limits the results returned Example:8
     * @queryParam per_page Number of results per page Example:5
     * 
	 */
    public function showAll(Request $request){
        $latest = $request->get('latest');
        if( !$latest || $latest > 1000){
            $latest = 1000;
        }
        $paginate = $request->get('per_page',50);

        $outputs= output::query()
            ->selectRaw('address, max(created_at) as last_transaction, count(address) as transactions,min(created_at) as first_transaction')
            ->orderBy('last_transaction', 'desc')
            ->groupBy('address')
            ->when($latest, function ($q, $latest) { 
                return $q->limit($latest);
            })
            ->simplePaginate($paginate);
        return response()->json($outputs);
    }

    /**
	 * Get single address
	 *
	 * Returns a specific address with first/last transaction date and transaction count
	 *
     * @queryParam address required A NKN wallet address
     * 
	 */
    public function show($address,Request $request){

        $outputs= output::query()
            ->where('address',$address)
            ->selectRaw('address, max(created_at) as last_transaction, count(address) as transactions,min(created_at) as first_transaction')
            ->orderBy('last_transaction', 'desc')
            ->groupBy('address')
            ->first();
        return response()->json($outputs);
    }

}