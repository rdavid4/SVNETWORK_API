<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompanyServiceZip extends Model
{
    use HasFactory;
    protected $table = 'company_service_zip';
    protected $fillable = ['company_id', 'service_id', 'zipcode_id', 'active', 'region_text', 'state_iso'];
    public function companyService()
    {
        return $this->belongsTo(CompanyService::class);
    }
    public function company()
    {
        return $this->belongsTo(Company::class);
    }
    public function service()
    {
        return $this->belongsTo(Service::class);
    }
    public function zipcode()
    {
        return $this->belongsTo(Zipcode::class);
    }


}
