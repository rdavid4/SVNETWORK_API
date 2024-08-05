<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use App\Models\User;
use App\Notifications\RenewPasswordNotification;

class RenewPasswordController extends Controller
{
    public function send(Request $request){
        $request->validate([
            'email' => 'required|email'
        ]);

        $email = $request->email;

        $user = User::where('email', $email)->first();

        if(!$user){
            return response()->noContent(201);
        }

        $verifyUrl = URL::temporarySignedRoute(
            'auth.change-password',
            now()->addMinutes(60),
            [
                'email'=> $email,
            ],
        );

        $api_url = config('app.api_url');
        $app_url = config('app.app_url');
        $url = str_replace($api_url, $app_url, $verifyUrl);
        $link =  strval($url);

        $user->notify(new RenewPasswordNotification($link));
        return response()->noContent(201);
    }

    public function verify(Request $request){

        if (!$request->hasValidSignature()) {
            abort(403);
        }


            $email = $request->email;
            $user = User::where('email', $email)->first();
            $token = 'Bearer '.$user->createToken('authToken')->plainTextToken;
            return response()->json([
                'access_token' => $token
            ]);


    }

}
