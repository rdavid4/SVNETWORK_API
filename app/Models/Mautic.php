<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Http;
class Mautic extends Model
{
    use HasFactory;

    public static function createContact(Array $data){
        $response = Http::withHeaders([
            'Authorization' => config('app.mautic_token'),
        ])->post(config('app.mautic_url').'/api/contacts/new', [
            'firstname' => $data['firstname'],
            'lastname' => $data['lastname'],
            'email' => $data['email'],
            'company' => $data['company'],
            'phone' => $data['phone'],
            'state' => $data['state'],
            'zipcode' => $data['zipcode'],
            'tags' => $data['tags'],
            'ipAddress' => $_SERVER['REMOTE_ADDR']
        ]);

        return $response;
    }
}
