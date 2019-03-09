<?php

namespace App\Http\Controllers;

use App\WalletAddress;
use App\Output;
use Illuminate\Http\Request;
use GuzzleHttp\Client as GuzzleHttpClient;
use GuzzleHttp\Exception\RequestException;
use JWTAuth;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use DB;

/**
 * @group User Wallets
 *
 * Endpoints for querying stored user wallets
 */
class WalletAddressController extends Controller
{
    /**
     * Get all wallets
     * Returns all stored wallets of currently logged in user
     *
     * @authenticated
     *
     * @response [
     *              {
     *                  "id": 1,
     *                  "label": "nknx",
     *                  "address": "NNP6M8EGZcWSZNgA2ebQfMVyNkwX6XXXXX",
     *                  "balance": 2541,
     *                  "user_id": 1,
     *                  "created_at": "2018-11-17 09:42:58",
     *                  "updated_at": "2018-11-17 09:42:58"
     *              },
     *              {
     *                  "id": 2,
     *                  "label": "nknx",
     *                  "address": "NNP6M8EGZcWSZNgA2ebQfMVyNkwX6XXXXX",
     *                  "balance": 2341,
     *                  "user_id": 1,
     *                  "created_at": "2018-11-17 09:42:58",
     *                  "updated_at": "2018-11-17 09:42:58"
     *              }
     * ]
	 */
    public function index()
    {
        // get all the walletAddresses
        $user = JWTAuth::parseToken()->authenticate();
        $walletAddresses = WalletAddress::where('user_id',$user->id)->get();
        return response()->json($walletAddresses);
    }

    /**
     * Store wallet
     * Store a wallet in the database
     *
     * @authenticated
     *
     * @bodyParam  address string required NKN wallet address
     * @bodyParam  label string An optional label of the node Example: nknx
     * @response {
     *      "status": "success",
     *      "data": {
     *                  "id": 2,
     *                  "label": "nknx",
     *                  "address": "NNP6M8EGZcWSZNgA2ebQfMVyNkwX6XXXXX",
     *                  "balance": 2341,
     *                  "user_id": 1,
     *                  "created_at": "2018-11-17 09:42:58",
     *                  "updated_at": "2018-11-17 09:42:58"
     *              }
     * }
     */
    public function store(Request $request)
    {
        $user = JWTAuth::parseToken()->authenticate();
        $address = $request->input('address');
        $label = $request->input('label','');
        $balance = 0;
        if (!$address ) {
            return response([
                'status' => 'error',
                'error' => 'address.empty',
                'msg' => 'address is not provided'
            ], 400);
        }
        else if (WalletAddress::where([['address','=',$address],['user_id','=', $user->id]])->first()){
            return response([
                'status' => 'error',
                'error' => 'duplicate.address',
                'msg' => 'Address already in database'
            ], 400);
        } else {
            $requestContent = [
                'headers' => [
                    'Accept' => 'application/json',
                    'Content-Type' => 'application/json'
                ],
                'json' => [
                    "id" => 1,
                    "method" => "getunspendoutput",
                    "params" => [
                        "address" => $address,
                        "assetid" => "4945ca009174097e6614d306b66e1f9cb1fce586cb857729be9e1c5cc04c9c02"
                    ],
                    "jsonrpc" => "2.0"
                ]
            ];
            try {
                $walletAddress = new WalletAddress();
                $walletAddress->label = $label;
                $walletAddress->address = $address;
                $walletAddress->user_id = $user->id;
                $client = new GuzzleHttpClient();
                $apiRequest = $client->Post('https://nknx.org:30003', $requestContent);
                $response = json_decode($apiRequest->getBody(), true);
                if($response["result"]){
                    foreach($response["result"] AS $unspendoutput) {
                        $balance = $balance + $unspendoutput["Value"];
                    }
                }
                else{
                    $balance = 0;
                }

                $walletAddress->balance = $balance;

                $walletAddress->save();

                return response([
                    'status' => 'success',
                    'data' => $walletAddress
                ]);


            } catch (RequestException $re) {
                return response([
                    'status' => 'error',
                    'error' => 'walletAddress.incorrect',
                    'msg' => 'wallet not existing'
                ], 400);
            }



        }
    }

    /**
	 * Get single wallet by walletAddress
	 * Returns a specific user-wallet based on the address
     *
	 * @authenticated
	 *
     * @queryParam walletAddress a stored nkn wallet address
     *
     * @response {
     *                  "id": 2,
     *                  "label": "nknx",
     *                  "address": "NNP6M8EGZcWSZNgA2ebQfMVyNkwX6XXXXX",
     *                  "balance": 2341,
     *                  "user_id": 1,
     *                  "created_at": "2018-11-17 09:42:58",
     *                  "updated_at": "2018-11-17 09:42:58"
     *              }
     *
     */
    public function show($walletAddress)
    {
        $user = JWTAuth::parseToken()->authenticate();
        $walletAddress = WalletAddress::where([['address','=', $walletAddress],['user_id','=', $user->id]])->first();
        return response()->json($walletAddress);
    }

    /**
	 * Remove single wallet by id
	 * Remove the specified user-wallet from the database
     *
	 * @authenticated
	 *
     * @queryParam walletAddress required Id of the resource
     *
     * @response {
     *  null
     * }
     */
    public function destroy(WalletAddress $walletAddress)
    {
        if($walletAddress)
            $walletAddress->delete();
        else
            return response()->json(error);
        return response()->json(null);
    }

    /**
	 * Get mining output per day
	 * Get the daily mining output based on a database-wallet id
     *
	 * @authenticated
	 *
     * @queryParam walletAddress required Id of the resource Example:36
     * @queryParam latest Limits the days returned Example:7
     *
     * @response [
     *       {
     *           "count": 2172,
     *           "date": "2018-11-17"
     *       },
     *       {
     *           "count": 4145,
     *           "date": "2018-11-16"
     *       },
     *       {
     *           "count": 4129,
     *           "date": "2018-11-15"
     *       },
     *       {
     *           "count": 4075,
     *           "date": "2018-11-14"
     *       },
     *       {
     *           "count": 4124,
     *           "date": "2018-11-13"
     *       },
     *       {
     *           "count": 3956,
     *           "date": "2018-11-12"
     *       },
     *       {
     *           "count": 3302,
     *           "date": "2018-11-11"
     *       },
     *       {
     *           "count": 2363,
     *           "date": "2018-11-10"
     *       }
     *   ]
     */
    public function getMiningOutputDaily(WalletAddress $walletAddress,Request $request){
        $latest = $request->get('latest');
        $outputs_query = Output::select(DB::raw("COUNT(*) as count, DATE(timestamp) AS date"))
        ->when($latest, function ($q, $latest) {
            return $q->where('timestamp', '>=', Carbon::now()->subDays($latest));
        })
        ->whereHas('transaction', function($q){
            $q->where('txType', 0);
        })
        ->where("address",$walletAddress->address)
        ->orderBy(DB::raw('DATE(timestamp)'), 'desc')
        ->groupBy(DB::raw('DATE(timestamp)'));
        $outputs = $outputs_query
                ->get();
        return response()->json($outputs);
    }

    /**
	 * Get mining output per month
	 * Get the monthly mining output based on a database-wallet id
     *
	 * @authenticated
	 *
     * @queryParam walletAddress required Id of the resource Example:36
     * @queryParam latest Limits the months returned Example:7
     *
     * @response [
     *       {
     *           "count": 2172,
     *           "month": "8"
     *       },
     *       {
     *           "count": 4145,
     *           "month": "7"
     *       },
     *       {
     *           "count": 4129,
     *           "month": "6"
     *       },
     *       {
     *           "count": 4075,
     *           "month": "5"
     *       },
     *       {
     *           "count": 4124,
     *           "month": "4"
     *       },
     *       {
     *           "count": 3956,
     *           "month": "3"
     *       },
     *       {
     *           "count": 3302,
     *           "month": "2"
     *       },
     *       {
     *           "count": 2363,
     *           "month": "1"
     *       }
     *   ]
     */
    public function getMiningOutputMonthly(WalletAddress $walletAddress,Request $request){
        $latest = $request->get('latest');
        $outputs_query = Output::select(DB::raw("COUNT(*) as count, EXTRACT(MONTH from timestamp AS month"))
        ->when($latest, function ($q, $latest) {
            return $q->where('timestamp', '>=', Carbon::now()->subMonths($latest));
        })
        ->whereHas('transaction', function($q){
            $q->where('txType', 0);
        })
        ->where("address",$walletAddress->address)
        ->orderBy(DB::raw('EXTRACT(MONTH from timestamp'), 'desc')
        ->groupBy(DB::raw('EXTRACT(MONTH from timestamp'));
        $outputs = $outputs_query
                ->get();
        return response()->json($outputs);
    }

}
