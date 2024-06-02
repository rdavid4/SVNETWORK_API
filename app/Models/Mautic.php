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
            'company' => $data['company'] ?? null,
            'phone' => $data['state']?? null,
            'state' => $data['state']?? null,
            'country' => $data['country']?? null,
            'city' => $data['city']?? null,
            'zipcode' => $data['zipcode'] ?? null,
            'tags' => $data['tags'],
            'ipAddress' => $_SERVER['REMOTE_ADDR']
        ]);

        return $response;
    }
}
