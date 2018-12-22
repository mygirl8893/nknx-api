<?php

namespace App\Http\Controllers;

use App\Node;
use App\User;
use Illuminate\Http\Request;
use GuzzleHttp\Client as GuzzleHttpClient;
use GuzzleHttp\Exception\RequestException;
use JWTAuth;
use Illuminate\Support\Facades\Auth;

/**
 * @group User Nodes
 *
 * Endpoints for querying user nodes
 */
class NodeController extends Controller
{
    /**
     * Get all nodes
     * Returns all stored nodes of currently logged in user
     * 
     * @authenticated
     * 
     * @response [   
     *              {       
     *                  "id": 172,
     *                  "label": "nknx",
     *                  "state": 0,
     *                  "syncState": "PersistFinished",
     *                  "port": 0,
     *                  "nodePort": 0,
     *                  "chordPort": null,
     *                  "jsonPort": 30003,
     *                  "wsPort": 30002,
     *                  "addr": "159.65.200.254",
     *                  "time": 1541687107532520392,
     *                  "version": 0,
     *                  "services": null,
     *                  "relay": 0,
     *                  "height": 0,
     *                  "txnCnt": 1,
     *                  "rxTxnCnt": 8510,
     *                  "chordID": "3eda464223dbae834131ac7ea964f7a273933bfaf8168786f5efead910015815",
     *                  "softwareVersion": "0.5.0-alpha-7-g2613",
     *                  "latestBlockHeight": 207011,
     *                  "online": 1,
     *                  "user_id": 2,
     *                  "created_at": "2018-11-04 15:51:49",
     *                  "updated_at": "2018-11-17 12:46:08"
     *              },
     *              {       
     *                  "id": 173,
     *                  "label": "nknx1",
     *                  "state": 0,
     *                  "syncState": "PersistFinished",
     *                  "port": 0,
     *                  "nodePort": 0,
     *                  "chordPort": null,
     *                  "jsonPort": 30003,
     *                  "wsPort": 30002,
     *                  "addr": "159.65.200.221",
     *                  "time": 1541687107532520465,
     *                  "version": 0,
     *                  "services": null,
     *                  "relay": 0,
     *                  "height": 0,
     *                  "txnCnt": 1,
     *                  "rxTxnCnt": 8510,
     *                  "chordID": "60665856ba544df2ceb7e8a4047f35872a82c8a1056fe3fdc00ca233b651c212",
     *                  "softwareVersion": "0.5.2-alpha",
     *                  "latestBlockHeight": 207012,
     *                  "online": 1,
     *                  "user_id": 2,
     *                  "created_at": "2018-11-04 15:52:12",
     *                  "updated_at": "2018-11-17 12:50:24"
     *              }
     *          ]
     */
    public function index()
    {
        $user = JWTAuth::parseToken()->authenticate();
        $nodes = Node::where('user_id',$user->id)->get();
        return response()->json($nodes);

    }

    /**
     * Store wallet
     * Store one or multiple nodes in the database
     * 
     * @authenticated
     * 
     * @bodyParam  ip string required One or multiple ips/domain names (comma separated) to store in the database Example: 104.248.138.60
     * @bodyParam  label string An optional label of the node Example: nknx
     * @response {
     *      "status": "success",
     *      "data": {
     *          "saved": [   
     *              {       
     *                  "id": 172,
     *                  "label": "nknx",
     *                  "state": 0,
     *                  "syncState": "PersistFinished",
     *                  "port": 0,
     *                  "nodePort": 0,
     *                  "chordPort": null,
     *                  "jsonPort": 30003,
     *                  "wsPort": 30002,
     *                  "addr": "104.248.138.60",
     *                  "time": 1541687107532520392,
     *                  "version": 0,
     *                  "services": null,
     *                  "relay": 0,
     *                  "height": 0,
     *                  "txnCnt": 1,
     *                  "rxTxnCnt": 8510,
     *                  "chordID": "3eda464223dbae834131ac7ea964f7a273933bfaf8168786f5efead910015815",
     *                  "softwareVersion": "0.5.0-alpha-7-g2613",
     *                  "latestBlockHeight": 207011,
     *                  "online": 1,
     *                  "user_id": 2,
     *                  "created_at": "2018-11-04 15:51:49",
     *                  "updated_at": "2018-11-17 12:46:08"
     *              }
     *          ],
     *          "failed": [
     *                  "192.168.178.44",
     *                  "192.168.178.32"
     *          ],
     *          "duplicate": [
     *                  "192.168.178.44",
     *                  "192.168.178.32"
     *          ]
     *      } 
     * }
     */
    public function store(Request $request)
    {
        $user = JWTAuth::parseToken()->authenticate();
        $aliases = explode(',', $request->input('ip'));
        $label = $request->input('label','');
        $multiAliases = [];
        $failedAliases = [];
        $savedAliases = [];
        if (!count($aliases) ) {
            return response([
                'status' => 'error',
                'error' => 'ip.empty',
                'msg' => 'no ips or aliases is not provided'
            ], 400);
        }
        foreach ($aliases as $alias) {
        
            if(substr($alias, -1) == '/') {
                $alias = substr($alias, 0, -1);
            }

            if (Node::where('alias','LIKE', '%'.$alias.'%')->first()){
                array_push($multiAliases,$alias);
            } else {
                //get main node data
                $requestContent = [
                    'timeout' => 1,
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
                    $apiRequest = $client->Post($alias . ':30003', $requestContent);        
                    $response = json_decode($apiRequest->getBody(), true);

                    unset($response["result"]["ID"]);

                    $node = new Node($response["result"]);
                    $node->online = true;
                    $node->label = $label;
                    $node->alias = $alias;
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
                        $apiRequest = $client->Post($alias.':30003', $requestContent);        
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
                            $apiRequest = $client->Post($alias.':30003', $requestContent);        
                            $response = json_decode($apiRequest->getBody(), true);
                            $node->latestBlockHeight = $response["result"];

                            $node->save();
                            array_push($savedAliases,$node);

                        } catch (RequestException $re) {
                            array_push($failedAliases,$alias);
                        }
        
                    } catch (RequestException $re) {
                        array_push($failedAliases,$alias);
                    }

                } catch (RequestException $re) {
                    return response([
                        'status' => 'failure 1',
                    ],400);
                    array_push($failedAliases,$alias);
                }  
            }
        }

        return response([
            'status' => 'success',
            'data' => [
                'saved' => $savedAliases,
                'failed' => $failedAliases,
                'duplicate' => $multiAliases
            ]
        ],200);
    }

    /**
	 * Get single node by id
	 * Returns a specific user-node based on the id 
     * 
	 * @authenticated
	 *
     * @queryParam node required Id of the resource
     * 
     * @response {    
     *                  "id": 172,
     *                  "label": "nknx",
     *                  "state": 0,
     *                  "syncState": "PersistFinished",
     *                  "port": 0,
     *                  "nodePort": 0,
     *                  "chordPort": null,
     *                  "jsonPort": 30003,
     *                  "wsPort": 30002,
     *                  "addr": "159.65.200.254",
     *                  "time": 1541687107532520392,
     *                  "version": 0,
     *                  "services": null,
     *                  "relay": 0,
     *                  "height": 0,
     *                  "txnCnt": 1,
     *                  "rxTxnCnt": 8510,
     *                  "chordID": "3eda464223dbae834131ac7ea964f7a273933bfaf8168786f5efead910015815",
     *                  "softwareVersion": "0.5.0-alpha-7-g2613",
     *                  "latestBlockHeight": 207011,
     *                  "online": 1,
     *                  "user_id": 2,
     *                  "created_at": "2018-11-04 15:51:49",
     *                  "updated_at": "2018-11-17 12:46:08"
     * }
     * 
     */
    public function show(Node $node)
    {
        $node = Node::find($node);
        return response()->json($node);
    }


    /**
	 * Remove single node by id
	 * Remove the specified user-node from the database
     * 
	 * @authenticated
	 *
     * @queryParam node required Id of the resource
     * 
     * @response {
     *  null
     * }
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
	 * Remove all user-nodes 
	 * Remove all user-nodes from the database 
     * 
	 * @authenticated
	 *
     * @response {
     *  null
     * }
     */
    public function destroyAll()
    {
        $user = JWTAuth::parseToken()->authenticate();
        Node::where('user_id',$user->id)->delete();
        return response()->json(null); 
    }
}
