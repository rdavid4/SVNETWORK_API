<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Company;

class ShareController extends Controller
{
    public function companies($slug){
        $company = Company::where('slug', $slug)->first();
        return view('companies.share', ['company' => $company]);
    }
}
