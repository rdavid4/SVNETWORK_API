<?php

namespace App\Http\Controllers;

use App\Http\Resources\ZipcodeResource;
use App\Models\Zipcode;
use Illuminate\Http\Request;

class ZipcodeController extends Controller
{
    public function show($zipcode){
        $zipcode = Zipcode::where('zipcode', $zipcode)->firstOrFail();
        return new ZipcodeResource($zipcode);
    }
    public function list(){
        $zipcode = Zipcode::all();
        return $zipcode;
    }
}
