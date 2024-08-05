<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Str;
use Carbon\Carbon;
class Project extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'user_id',
        'zipcode_id',
        'service_id'
    ];

    protected static function boot()
    {
        parent::boot();

        // Registering the creating event
        static::creating(function ($model) {
            $model->uuid = Str::uuid(); // Genera un UUID único
        });
    }

    public function images()
    {
        return $this->morphMany(Image::class, 'imageable');
    }

    public function companies():BelongsToMany
    {
        return $this->BelongsToMany(Company::class);
    }
    public function answers():BelongsToMany
    {
        return $this->BelongsToMany(Answer::class);
    }

    public function user():BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getDateAttribute()
    {
        return  date_format($this->created_at, 'm/d/Y h:i A');
    }

    public function getDateHumansAttribute()
    {
        $date1 = Carbon::parse($this->created_at);
        $date2 = Carbon::now(); // Represents the current date and time
        $diff = $date1->diffForHumans($date2);
        return  $diff;
    }
    public function getTagsAttribute()
    {
        $hasImage = $this->images->count() ? true: false;

        return  [
            'has_image' => $hasImage
        ];
    }
    public function matches(){
        return $this->hasMany(Matches::class);
    }
}
