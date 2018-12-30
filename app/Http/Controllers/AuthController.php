<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\RegisterFormRequest;
use App\Http\Requests\ChangeUserFormRequest;
use App\User;
use App\NotificationsConfig;
use App\VerifyUser;
use App\PasswordReset;
use App\Mail\VerifyMail;
use App\Mail\PasswordResetMail;
use Mail;
use JWTAuth;
use Illuminate\Support\Facades\Auth;

/**
 * @group Auth and User management
 *
 * APIs for managing users
 */
class AuthController extends Controller
{
    /**
	 * Register a user
	 *
	 * Creates an initial user entity in the database and also starts verification process
     *
     * @bodyParam  email string required User email address
     * @bodyParam  name string required  Username
     * @bodyParam  password string required  User password
     *
     * @response {
     *      "status": "success",
     *      "data": {
     *          "id" : 1,
     *          "name": "ChrisT",
     *          "email": "test@nknx.org",
     *          "verified": false,
     *          "created_at": "2018-10-15 09:50:55",
     *          "updated_at": "2018-10-15 09:51:37"
     *      }
     * }
	 */
    public function register(RegisterFormRequest $request)
    {
        $user = new User;
        $user->email = $request->email;
        $user->name = $request->name;
        $user->password = bcrypt($request->password);
        $user->save();

        $notificationsConfig = NotificationsConfig::create([
            'user_id' => $user->id,
            'nodeOffline' => false,
            'nodeOutdated' => false,
            'nodeStucked'  => false,
            'weeklyMiningOutput'  => false
        ]);

        $verifyUser = VerifyUser::create([
            'user_id' => $user->id,
            'token' => str_random(40)
        ]);

        Mail::to($user->email)->send(new VerifyMail($user));

        return response([
            'status' => 'success',
            'data' => $user
        ], 200);
    }

    /**
	 * Resend verification mail
	 * Recreates VerifyUser entity and resends the verification mail
     *
	 * @authenticated
	 *
     * @response {
     *      "status": "success",
     *      "data": ""
     * }
	 */
    public function resendVerification()
    {
        $user = User::find(Auth::user()->id);

        VerifyUser::where('user_id', $user->id)->delete();

        $verifyUser = VerifyUser::create([
            'user_id' => $user->id,
            'token' => str_random(40)
        ]);

        Mail::to($user->email)->send(new VerifyMail($user));

        return response([
            'status' => 'success',
            'data' => ''
        ], 200);
    }

    /**
	 * Reset password
	 *
	 * Creates a password reset mail and an entry in the database
     *
     * @bodyParam  email string required User email address
	 *
     * @response {
     *      "status": "success",
     *      "data": ""
     * }
	 */
    public function resetPassword(Request $request){
        $user = User::where('email', $request->email)->latest()->first();
        $passwordReset = PasswordReset::create([
            'user_id' => $user->id,
            'token' => str_random(40)
        ]);

        Mail::to($user->email)->send(new PasswordResetMail($user));

        return response([
            'status' => 'success',
            'data' => ''
        ], 200);
    }

    /**
	 * Set new password
	 *
	 * Sets a new password for a user from a provided token
     *
     * @bodyParam  password string required  User password
     *
     * @response {
     *      "status": "success",
     *      "msg": "Password changed successfully."
     * }
	 *
	 */
    public function setNewPasswordFromToken(Request $request, $token){
        $passwordReset = PasswordReset::where('token', $token)->latest()->first();
        if(isset($passwordReset) ){
            $user = $passwordReset->user;
            $user->password = bcrypt($request->password);
            $passwordReset->user->save();
            PasswordReset::where('token', $token)->delete();
            return response([
                'status' => 'success',
                'msg' => 'Password changed successfully.'
            ], 200);
        }else{
            return response([
                'status' => 'error',
                'error' => 'error'
            ], 400);
        }
    }

    /**
	 * Login
	 *
	 * Logs a user in
     *
     * @bodyParam  email string required User email address
     * @bodyParam  password string required  User password
     *
     * @response {
     *      "status": "success"
     * }
	 *
	 */
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');
        if ( ! $token = JWTAuth::attempt($credentials)) {
                return response([
                    'status' => 'error',
                    'error' => 'invalid.credentials',
                    'msg' => 'Invalid Credentials.'
                ], 400);
        }
        return response([
                'status' => 'success'
            ])
            ->header('Authorization', $token);
    }

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
        $user = User::find(Auth::user()->id);
        $user->walletcount = $user->walletAddresses()->count();
        $user->nodecount = $user->nodes()->count();
        return response([
                'status' => 'success',
                'data' => $user
            ]);
    }

    /**
	 * Refresh
	 * Refreshes the current users session
     *
	 * @authenticated
	 *
     * @response {
     *      "status": "success"
     * }
	 */
    public function refresh()
    {
        return response([
                'status' => 'success'
            ]);
    }

    /**
	 * Verify email
	 *
	 * Accepts a token provided in an eMail link and verifies the user entity
     *
     * @queryParam token required The verification token Example:
     *
     * @response {
     *      "status": "Your e-mail is verified. You can now login."
     * }
	 *
	 */
    public function verifyUser($token)
    {
        $verifyUser = VerifyUser::where('token', $token)->latest()->first();
        if(isset($verifyUser) ){
            $user = $verifyUser->user;
            if(!$user->verified) {
                $verifyUser->user->verified = 1;
                $verifyUser->user->save();
                VerifyUser::where('token', $token)->delete();
                $status = "Your e-mail is verified. You can now login.";
            }else{
                $status = "Your e-mail is already verified. You can now login.";
            }
        }else{
            return redirect('https://nknx.org/login')->with('warning', "Sorry your email cannot be identified.");
        }

        return redirect('https://nknx.org/login')->with('status', $status);
    }

    /**
	 * Logout
	 * Logs a user out
     *
	 * @authenticated
	 *
     * @response {
     *      "status": "success",
     *      "msg": "Logged out successfully."
     * }
	 */
    public function logout()
    {
        JWTAuth::invalidate();
        return response([
                'status' => 'success',
                'msg' => 'Logged out successfully.'
            ], 200);
    }

    /**
	 * Change a user
	 *
	 * Changes the userdata based on the given values
     *
     * @bodyParam  email string required User email address
     * @bodyParam  name string required  Username
     * @bodyParam  password string required  User password
     *
	 */
    public function changeUser(ChangeUserFormRequest $request)
    {
        $user = User::find(Auth::user()->id);
        if($request->email){
            $user->email = $request->email;
        }
        if($request->name){
            $user->name = $request->name;
        }
        if($request->password){
            $user->password = bcrypt($request->password);
        }
        $user->save();
        return response([
            'status' => 'success',
            'data' => $user
        ], 200);
    }
}
