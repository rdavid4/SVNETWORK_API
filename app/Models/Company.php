<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;
use Cviebrock\EloquentSluggable\Sluggable;

class Company extends Model
{
    use HasFactory, Sluggable;
    public $timestamps = true;
    protected $fillable = [
        'name',
        'uuid',
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

    protected static function boot()
    {
        parent::boot();

        // Registering the creating event
        static::creating(function ($model) {
            $model->uuid = Str::uuid(); // Genera un UUID único
        });
    }

    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'name'
            ]
        ];
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class);
    }
    public function states(): BelongsToMany
    {
        return $this->belongsToMany(State::class)->withTimestamps();
    }
    public function services(): BelongsToMany
    {
        return $this->belongsToMany(Service::class)->withPivot('pause');
    }
    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class)->withTimestamps();
    }

    public function state(){
        return $this->belongsTo(State::class);
    }
}
