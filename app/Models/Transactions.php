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
        'payment_code'
    ];
}
