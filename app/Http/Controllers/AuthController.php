<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\RegisterFormRequest;
use App\User;
use App\VerifyUser;
use App\PasswordReset;
use App\Mail\VerifyMail;
use App\Mail\PasswordResetMail;
use Mail;
use JWTAuth;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function register(RegisterFormRequest $request)
    {
        $user = new User;
        $user->email = $request->email;
        $user->name = $request->name;
        $user->password = bcrypt($request->password);
        $user->save();

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

    public function resetPassword(Request $request){
        //creates a password reset mail and an entry in the database
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

    public function setNewPasswordFromToken(Request $request, $token){
        $passwordReset = PasswordReset::where('token', $token)->latest()->first();
        if(isset($passwordReset) ){
            $user = $passwordReset->user;
            $user->password = bcrypt($request->password);
            $passwordReset->user->save();
            PasswordReset::where('token', $token)->delete();
            return response([
                'status' => 'success',
                'msg' => 'Password changed Successfully.'
            ], 200);
        }else{
            return response([
                'status' => 'error',
                'error' => 'error',

            ], 400);
        }
    }

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

    public function user(Request $request)
    {
        $user = User::find(Auth::user()->id);
        return response([
                'status' => 'success',
                'data' => $user
            ]);
    }
    public function refresh()
    {
        return response([
                'status' => 'success'
            ]);
    }

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
            return redirect('/login')->with('warning', "Sorry your email cannot be identified.");
        }

        return redirect('/login')->with('status', $status);
    }

    public function logout()
    {
        JWTAuth::invalidate();
        return response([
                'status' => 'success',
                'msg' => 'Logged out Successfully.'
            ], 200);
    }
}
