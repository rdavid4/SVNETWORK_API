<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Notifications\UserVerification;
use Illuminate\Support\Facades\URL;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Auth;

class RegisterController extends Controller
{
    function register(Request $request){

        $request->validate([
            'email' => 'required|email|max:100',
            'password' => 'required|min:6'
        ]);


        $params = [
            'password' => $request->password,
            'email' => $request->email
        ];

        $user = User::create($params);

        $verifyUrl = URL::temporarySignedRoute(
            'auth.verify',
            now()->addMinutes(60),
            [
                'user' => $user->id
            ],
        );

        //Remplazamos la url de la api por la url de la app
        $api_url = config('app.url').'/app/';
        $web_url = config('app.web_url');
        $url = str_replace($api_url, $web_url, $verifyUrl);
        $link =  strval($url);
        $user->link = $link;

        $user->notify(new UserVerification($user));

        return response()->json(["message" => "Succesful user registration"], 201);
    }
    function registerGoogle(Request $request){

        $request->validate([
            'email' => 'required|email|max:100',
            'google_id'=>'required',
            'name'=>'required',
            'surname'=>'required',
            'image'=>'required',
        ]);

        $user = User::where('email', $request->get('email'))->first();

        if ($user) {
            $user->google_id = $request->google_id;
            $user->image = $request->image;
            $user->save();
        }else{
            $params = [
                'google_id' => $request->google_id,
                'name' => $request->name,
                'surname' => $request->surname,
                'image' => $request->image,
                'email' => $request->email
            ];

            $user = User::create($params);

            $user->markEmailAsVerified();
            $user->save();

        }

        return new UserResource($user);

    }
}
