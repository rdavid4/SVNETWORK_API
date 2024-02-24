<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{
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
