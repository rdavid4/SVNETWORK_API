<?php

namespace App\Http\Controllers;

use App\Models\Mautic;
use Illuminate\Http\Request;
use Mautic\Auth\ApiAuth;
use Illuminate\Support\Facades\Http;
class MauticController extends Controller
{
    public function __construct()
    {

    }

    public function token(Request $request){


    }

    public function callback(Request $request){
        $code = $request->get('code');
        return $code;
    }
}
