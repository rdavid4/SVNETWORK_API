<?php

namespace App\Http\Controllers;

use App\Events\UserContractorRegistered;
use Illuminate\Http\Request;
use App\Models\User;
use App\Notifications\UserVerification;
use Illuminate\Support\Facades\URL;
use App\Http\Resources\UserResource;
use App\Models\Mautic;
use App\Notifications\CompanyCreatedNotification;
use App\Notifications\UserCreatedNotification;
use Exception;
use Illuminate\Support\Facades\Auth;

class RegisterController extends Controller
{
    public function __construct() {

    }

    function register(Request $request){
        $request->validate([
            'email' => 'required|email|max:100',
            'password' => 'required|min:6',
            'phone' => 'required',
            'name' => 'required',
            'surname' => 'required'
        ]);


        $params = [
            'password' => $request->password,
            'email' => $request->email,
            'name' => $request->name,
            'phone' => $request->phone,
            'surname' => $request->surname
        ];

        $user = User::create($params);

        try{
            $data = [
                'firstname' => $user->name,
                'lastname' => $user->surname,
                'email' => $user->email,
                'phone' => $user->phone,
                'tags' => 'user'
            ];
            return Mautic::createContact($data);
        }catch(Exception $e){

        }

        $verifyUrl = URL::temporarySignedRoute(
            'auth.verify',
            now()->addMinutes(60),
            [
                'user' => $user->id
            ],
        );

        //Remplazamos la url de la api por la url de la app
        $api_url = config('app.api_url');
        $app_url = config('app.app_url');
        $url = str_replace($api_url, $app_url, $verifyUrl);
        $link =  strval($url);
        $user->link = $link;

        $user->notify(new UserCreatedNotification($user));

        return response()->json(["message" => "Succesful user registration"], 201);
    }
    function registerCompany(Request $request){
        $request->validate([
            'name' => 'required',
            'surname' => 'required',
            'email' => 'required|email|max:100',
            'password' => 'required|min:6'
        ]);


        $params = [
            'name' => $request->name,
            'surname' => $request->surname,
            'password' => $request->password,
            'email' => $request->email,
            'pro' => 1
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
        $api_url = config('app.api_url');
        $app_url = config('app.app_url');
        $url = str_replace($api_url, $app_url, $verifyUrl);
        $link =  strval($url);
        $user->link = $link;

        UserContractorRegistered::dispatch($user);
        $user->notify(new UserCreatedNotification($user));
        return new UserResource($user);
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

        Auth::login($user);

        return new UserResource($user);

    }
}
