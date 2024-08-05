<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompanyService extends Model
{
    use HasFactory;
    protected $table = 'company_service';

    public function zipcodes(){
        return $this->hasMany(CompanyServiceZip::class);
    }

    public function service(){
        return $this->belongsTo(Service::class);
    }

    public function company(){
        return $this->belongsTo(Company::class);
    }
}
