<?php

namespace App\Http\Controllers;

use App\Models\Mautic;
use Illuminate\Http\Request;
use Mautic\Auth\ApiAuth;
class MauticController extends Controller
{
    public function __construct()
    {

    }

    public function createContact(){
        $data = [
            'firstname' => 'Roger',
            'lastname' => 'Quinonez',
            'email' => 'rogredavid4444@asdasd.com',
            'company' => 'Qsoftcom',
            'phone' => '2403551153',
            'state' => 'Maryland',
            'zipcode' => '20906',
            'tags' => 'company'
        ];
        return Mautic::createContact($data);
    }
}
