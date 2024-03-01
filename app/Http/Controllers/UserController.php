<?php

namespace App\Http\Controllers;

use App\Http\Resources\DashboardUserResource;
use App\Http\Resources\UserResource;
use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{
    public function list(){
        $users = User::where('is_admin', 0)->get();
        return DashboardUserResource::collection($users);
    }
    public function show(){
        $user = auth()->user();
        return new UserResource($user);
    }
    public function updatePassword(Request $request){
        $request->validate([
            'password' => 'required'
        ]);

        $email = auth()->user()->email;
        $user = User::where('email', $email)->first();
        $user->password = bcrypt($request->password);
        $user->save();

        return $user;
    }

    function emailExist($email)
    {
        $exist = User::where("email",$email)->whereNotNull('password')->exists();
        return $exist;
    }
}
