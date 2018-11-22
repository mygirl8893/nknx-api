<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;

/**
 * @group Port check
 *
 * Endpoints for the port checker
 */
class PortCheckController extends Controller
{
    /**
	 * Check port
	 *
	 * Checks port 30001, 30002 and 30003 of a given IP or address
     * @queryParam address required Address or hostname to check Example: https://nknx.org
     * 
     */ 
    public function checkPort(Request $request)
    {
        $address = $request->input('address');
        if (!$address) {
            return response([
                'status' => 'error',
                'error' => 'address.empty',
                'msg' => 'address is not provided'
            ], 400);
        }
        $disallowed = array('http://', 'https://');

        if(!filter_var($address, FILTER_VALIDATE_IP)){
            foreach($disallowed as $d) {
                if(strpos($address, $d) === 0) {
                    $address = str_replace($d, '', $address);
                }
            }
            $host = gethostbyname($address);
        }
        else{
            $host = $address;
        }
        $ports = array(30001, 30002, 30003);
        $response = [];

        foreach ($ports as $port)
        {
            $connection = @fsockopen($host, $port,$errno,$errstr,1);

            if (is_resource($connection))
            {
                $response[$port] = "open";

                fclose($connection);
            }

            else
            {
                $response[$port] = "closed";
            }
        }  
        return response()->json($response);

    }
}