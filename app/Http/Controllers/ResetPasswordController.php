<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use App\Models\User;
class ResetPasswordController extends Controller
{
    public function send(Request $request){
        $request->validate([
            'email' => 'required|email'
        ]);

        $email = $request->email;

        $user = User::where('email', $email)->first();

        if(!$user){
            abort(422, 'Usuario no registrado');
        }

        $verifyUrl = URL::temporarySignedRoute(
            'auth.change-password',
            now()->addMinutes(60),
            [
                'email'=> $email,
            ],
        );

        $api_url = config('app.url');
        $web_url = config('app.web_url');
        $url = str_replace($api_url, $web_url, $verifyUrl);
        $link =  strval($url);

        // $user->notify(new RenewPasswordNotification($link));
        return $link;
    }

    public function verify(Request $request){

        if (!$request->hasValidSignature()) {
            abort(403, 'Invalid email signature');
        }


            $email = $request->email;
            $user = User::where('email', $email)->first();

            return  $user;


    }
}
