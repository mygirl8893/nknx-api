<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;

class PortCheckController extends Controller
{
    public function checkPort(Request $request)
    {
        $address = $request->input('address');
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
        set_time_limit(3);
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