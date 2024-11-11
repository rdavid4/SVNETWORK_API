<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Matches extends Model
{
    use HasFactory;
    const REFUND_REQUESTED = 0;
    const REFUND_PAID = 1;
    const REFUND_DENIED = 2;

    protected $fillable =[
        'email',
        'company_id',
        'service_id',
        'project_id',
        'user_id',
        'refund_status'
    ];
    protected $dates = ['deleted_at'];

    public function service():BelongsTo
    {
        return $this->belongsTo(Service::class, 'user_id', 'id');
    }
    public function client():BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
    public function project():BelongsTo
    {
        return $this->belongsTo(Project::class, 'project_id', 'id');
    }
    public function company():BelongsTo
    {
        return $this->belongsTo(Company::class);
    }
}
