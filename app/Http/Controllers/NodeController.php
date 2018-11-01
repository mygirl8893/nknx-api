<?php

namespace App\Http\Controllers;

use App\Node;
use App\User;
use Illuminate\Http\Request;
use GuzzleHttp\Client as GuzzleHttpClient;
use GuzzleHttp\Exception\RequestException;
use JWTAuth;
use Illuminate\Support\Facades\Auth;

class NodeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // get all the nodes
        $user = JWTAuth::parseToken()->authenticate();
        $nodes = Node::where('user_id',$user->id)->get();
        return response()->json($nodes);

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
        $ip = $request->input('ip');
        $label = $request->input('label','');
        if (!$ip ) {
            return response([
                'status' => 'error',
                'error' => 'ip.empty',
                'msg' => 'ip is not provided'
            ], 400);
        }
        else if (Node::where('addr','LIKE', '%'.$ip.'%')->first()){
            return response([
                'status' => 'error',
                'error' => 'duplicate.ip',
                'msg' => 'ip already in database'
            ], 400);
        } else {
            //get main node data
            $requestContent = [
                'timeout' => 0.5,
                'headers' => [
                    'Accept' => 'application/json',
                    'Content-Type' => 'application/json'
                ],
                'json' => [
                    "id" => 1,
                    "method" => "getnodestate",
                    "params" => [
                        "provider" => "nknx",
                    ],
                    "jsonrpc" => "2.0"
                ]
            ];
            try {
                $client = new GuzzleHttpClient();
                $apiRequest = $client->Post($ip . ':30003', $requestContent);        
                $response = json_decode($apiRequest->getBody(), true);

                unset($response["result"]["ID"]);

                $node = new Node($response["result"]);
                $node->online = true;
                $node->label = $label;
                $node->user_id =  $user->id;
                //get version
                $requestContent = [
                    'headers' => [
                        'Accept' => 'application/json',
                        'Content-Type' => 'application/json'
                    ],
                    'json' => [
                        "id" => 1,
                        "method" => "getversion",
                        "params" => [
                            "provider" => "nknx",
                        ],
                        "jsonrpc" => "2.0"
                    ]
                ];


                try {
                    $client = new GuzzleHttpClient();
                    $apiRequest = $client->Post($ip.':30003', $requestContent);        
                    $response = json_decode($apiRequest->getBody(), true);
                    $node->softwareVersion = $response["result"];

                    //get latestblockheight
                    $requestContent = [
                        'headers' => [
                            'Accept' => 'application/json',
                            'Content-Type' => 'application/json'
                        ],
                        'json' => [
                            "id" => 1,
                            "method" => "getlatestblockheight",
                            "params" => [
                                "provider" => "nknx",
                            ],
                            "jsonrpc" => "2.0"
                        ]
                    ];
                    try {
                        $client = new GuzzleHttpClient();
                        $apiRequest = $client->Post($ip.':30003', $requestContent);        
                        $response = json_decode($apiRequest->getBody(), true);
                        $node->latestBlockHeight = $response["result"];

                        $node->save();

                        return response([
                            'status' => 'success',
                            'data' => $node
                        ]);
                        
        
                    } catch (RequestException $re) {
                        return response([
                            'status' => 'error',
                            'error' => 'node.unreachable',
                            'msg' => 'node not reachable'
                        ], 400);
                    }
    
                } catch (RequestException $re) {
                    return response([
                        'status' => 'error',
                        'error' => 'node.unreachable',
                        'msg' => 'node not reachable'
                    ], 400);
                }

            } catch (RequestException $re) {
                return response([
                    'status' => 'error',
                    'error' => 'node.unreachable',
                    'msg' => 'node not reachable'
                ], 400);
            }  

        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Node  $node
     * @return \Illuminate\Http\Response
     */
    public function show(Node $node)
    {
        $node = Node::find($node);
        return response()->json($node);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Node  $node
     * @return \Illuminate\Http\Response
   
    public function update(Request $request, Node $node)
    {
        //
    }  */

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Node  $node
     * @return \Illuminate\Http\Response
     */
    public function destroy(Node $node)
    {
        if($node)
            $node->delete(); 
        else
            return response()->json(error);
        return response()->json(null); 
    }

    /**
     * Remove the specified resource from storage.
     *
     * 
     * @return \Illuminate\Http\Response
     */
    public function destroyAll()
    {
        $user = JWTAuth::parseToken()->authenticate();
        Node::where('user_id',$user->id)->delete();
        return response()->json(null); 
    }
}
