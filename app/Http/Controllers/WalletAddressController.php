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

class WalletAddressController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // get all the walletAddresses
        $user = JWTAuth::parseToken()->authenticate();
        $walletAddresses = WalletAddress::where('user_id',$user->id)->get();
        return response()->json($walletAddresses);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
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
        else if (WalletAddress::where('address', $address)->first()){
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
                $apiRequest = $client->Post('http://testnet-seed-0002.nkn.org:30003', $requestContent);        
                $response = json_decode($apiRequest->getBody(), true);

                foreach($response["result"] AS $unspendoutput) {
                    $balance = $balance + $unspendoutput["Value"];
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
     * Display the specified resource.
     *
     * @param  \App\WalletAddress  $walletAddress
     * @return \Illuminate\Http\Response
     */
    public function show(WalletAddress $walletAddress)
    {
        $walletAddress = WalletAddress::find($walletAddress);
        return response()->json($walletAddress);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\WalletAddress  $walletAddress
     * @return \Illuminate\Http\Response
     */
    /*public function update(Request $request, WalletAddress $walletAddress)
    {
        //
    }*/

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\WalletAddress  $walletAddress
     * @return \Illuminate\Http\Response
     */
    public function destroy(WalletAddress $walletAddress)
    {
        if($walletAddress)
            $walletAddress->delete(); 
        else
            return response()->json(error);
        return response()->json(null); 
    }

    public function getMiningOutput(WalletAddress $walletAddress){
        $outputs_query = Output::select(DB::raw("COUNT(*) as count, DATE(created_at) AS date"))
        ->whereHas('transaction', function($q){
            $q->where('txType', 0);
        })
        ->where("address",$walletAddress->address)
        ->orderBy(DB::raw('DATE(created_at)'), 'desc')
        ->groupBy(DB::raw('DATE(created_at)'));
        $outputs = $outputs_query
                ->get();
        return response()->json($outputs);
    }

}
