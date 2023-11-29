<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{
    function emailExist($email)
    {
        $exist = User::where("email",$email)->whereNotNull('password')->exists();
        return $exist;
    }
}
