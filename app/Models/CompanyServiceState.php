<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompanyServiceState extends Model
{
    use HasFactory;
    protected $table = 'company_service_state';
    protected $fillable = ['company_service_id', 'state_id'];
    public function state(){
        return $this->belongsTo(State::class);
    }
}
