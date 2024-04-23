<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class NoMatches extends Model
{
    use HasFactory;

    protected $fillable =[
        'email',
        'company_id',
        'service_id',
        'project_id',
        'user_id'
    ];

    public function client():BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    public function company():BelongsTo
    {
        return $this->belongsTo(Company::class);
    }
}
