<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;


use App\CrawledNode;

/**
 * @group Node crawler
 *
 * Endpoints for querying crawled Nodes
 */
class CrawledNodesController extends Controller
{
    /**
	 * Get all nodes
	 *
	 * Returns a list of all node-ips crawled by the API
     * @queryParam withLocation Add location data to the result
     * 
     * @response [
     *  {
     *      "id": 3,
     *      "ip": "35.187.201.101",
     *      "created_at": "2018-11-01 08:29:37",
     *      "updated_at": "2018-11-01 08:29:39",
     *      "continent_code": "NA",
     *      "continent_name": "North America",
     *      "country_code2": "US",
     *      "country_code3": "USA",
     *      "country_name": "United States",
     *      "country_capital": "Washington",
     *      "state_prov": "Virginia",
     *      "district": "Loudoun",
     *      "city": "Ashburn",
     *      "zipcode": "20149",
     *      "latitude": "39.04380000",
     *      "longitude": "-77.48740000",
     *      "is_eu": "0",
     *      "calling_code": "+1",
     *      "country_tld": ".us",
     *      "isp": "Google LLC",
     *      "connection_type": "",
     *      "organization": "Google LLC",
     *      "geoname_id": "4744870"
     *  },
     *  {
     *      "id": 4,
     *      "ip": "35.198.198.253",
     *      "created_at": "2018-11-01 08:29:37",
     *      "updated_at": "2018-11-01 08:29:39",
     *      "continent_code": "AS",
     *      "continent_name": "Asia",
     *      "country_code2": "SG",
     *      "country_code3": "SGP",
     *      "country_name": "Singapore",
     *      "country_capital": "Singapore",
     *      "state_prov": "Central Singapore",
     *      "district": "Queenstown Estate",
     *      "city": "Singapore",
     *      "zipcode": "",
     *      "latitude": "1.27623000",
     *      "longitude": "103.80000000",
     *      "is_eu": "0",
     *      "calling_code": "+65",
     *      "country_tld": ".sg",
     *      "isp": "Google LLC",
     *      "connection_type": "",
     *      "organization": "Google LLC",
     *      "geoname_id": "1884386"
     *  },
     *  {
     *      "id": 7,
     *      "ip": "35.204.197.53",
     *      "created_at": "2018-11-01 08:29:37",
     *      "updated_at": "2018-11-01 08:29:39",
     *      "continent_code": "NA",
     *      "continent_name": "North America",
     *      "country_code2": "US",
     *      "country_code3": "USA",
     *      "country_name": "United States",
     *      "country_capital": "Washington",
     *      "state_prov": "Virginia",
     *      "district": "Loudoun",
     *      "city": "Ashburn",
     *      "zipcode": "20149",
     *      "latitude": "39.04380000",
     *      "longitude": "-77.48740000",
     *      "is_eu": "0",
     *      "calling_code": "+1",
     *      "country_tld": ".us",
     *      "isp": "Google LLC",
     *      "connection_type": "",
     *      "organization": "Google LLC",
     *      "geoname_id": "4744870"
     *  }
     *]
     *
	 */
    public function showAll(Request $request){
        $withLocation = $request->get('withLocation', false);
        if($withLocation){
            $nodes = CrawledNode::all();
        }
        else{
            $nodes = CrawledNode::all()->pluck('ip');
        }
       
        return response()->json($nodes);
    }

    

}
