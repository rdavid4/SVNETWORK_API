<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Company extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'description',
        'email',
        'city',
        'phone',
        'phone_2',
        'address_line1',
        'address_line2',
        'social_facebook',
        'social_x',
        'social_youtube',
        'zip_code',
        'video_url',
        'logo_url'
    ];

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(Company::class);
    }

    public function services(): HasMany
    {
        return $this->hasMany(Service::class);
    }
}
