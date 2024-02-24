<?php

namespace App\Http\Controllers;

use App\Models\Company;
use Illuminate\Http\Request;

class CompanyController extends Controller
{
    public function list(){
        return Company::all();
    }
    public function show(){

    }
    public function store(Request $request){
        $user = auth()->user();
    }

    public function update(){

    }

    public function delete(){

    }
}
