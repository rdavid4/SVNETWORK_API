<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transactions extends Model
{
    use HasFactory;
    protected  $fillable = [
        'user_id',
        'project_id',
        'service_id',
        'stripe_payment_method',
        'price',
        'paid',
        'message',
        'payment_code',
        'company_id'
    ];

    public function user(){
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
    public function project(){
        return $this->belongsTo(Project::class, 'project_id', 'id');
    }
    public function service(){
        return $this->belongsTo(Service::class, 'service_id', 'id');
    }
}
