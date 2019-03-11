<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;


/**
 * @group Auth and User management
 *
 * APIs for managing users and their configuration
 */
class AuthController extends Controller
{

    /**
	 * User
	 * Returns the current logged in User
     *
     * @authenticated
     *
     * @response {
     *      "status": "success",
     *      "data": {
     *          "id" : 1,
     *          "name": "ChrisT",
     *          "email": "test@nknx.org",
     *          "verified": true,
     *          "created_at": "2018-10-15 09:50:55",
     *          "updated_at": "2018-10-15 09:51:37"
     *      }
     * }
	 *
	 */
    public function user(Request $request)
    {

        $user = \Auth::user();

        return response([
                'status' => 'success',
                'data' => $user
            ]);
    }

}
