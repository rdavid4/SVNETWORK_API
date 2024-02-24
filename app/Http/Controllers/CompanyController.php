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
        $request->validate([
            'name' => 'required',
            'email' => 'required|email',
            'description' => 'required',
            'phone' => 'required',
            'address_line1' => 'required',
            'city' => 'required',
            'zip_code' => 'required',
            'video_url' => 'required',
        ]);

        $company = Company::create([
            'name' => $request->name,
            'email' => $request->email,
            'description' => $request->description,
            'phone' => $request->phone,
            'address_line1' => $request->address_line1,
            'city' => $request->city,
            'zip_code' => $request->zip_code,
            'video_url' => $request->video_url,
        ]);


        if($request->filled('phone_2')){
            $company->phone_2 = $request->phone_2;
        }

        if($request->filled('address_line2')){
            $company->address_line2 = $request->address_line2;
        }

        if($request->filled('social_facebook')){
            $company->social_facebook = $request->social_facebook;
        }

        if($request->filled('social_x')){
            $company->social_x = $request->social_x;
        }

        if($request->filled('social_youtube')){
            $company->social_youtube = $request->social_youtube;
        }

        if($request->filled('image')){

        }

        $company->save();

        return $company;

    }

    public function update(){

    }

    public function delete(){

    }
}
