<?php

namespace App\Http\Controllers;

use App\Http\Resources\StateResource;
use App\Models\State;
use Illuminate\Http\Request;

class StateController extends Controller
{
    public function list(){
        return State::all();
    }

    public function show($iso){
        $state = State::where('iso_code', $iso)->first();
        return new StateResource($state);
    }
}
