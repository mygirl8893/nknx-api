<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;

class PortCheckController extends Controller
{
    public function checkPort(Request $request)
    {
        $host = $request->input('address');
        set_time_limit(1);
        $ports = array(30000, 30001, 30002, 30003);
        $response = [];

        foreach ($ports as $port)
        {
            $connection = @fsockopen($host, $port,$errno,$errstr,0.1);

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