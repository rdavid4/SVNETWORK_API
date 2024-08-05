<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
class State extends Model
{
    use HasFactory;

    public function companies(): BelongsToMany
    {
        return $this->belongsToMany(Company::class);
    }

    public function zipcodes()
    {
        return $this->hasMany(Zipcode::class, 'state_iso', 'iso_code');
    }
    public function regions()
    {
        return $this->zipcodes->groupBy('region')->keys()->map(function($region){
            return ["name" => $region];
        });

    }
}
