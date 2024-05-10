<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\SoftDeletes;

class Company extends Model
{
    use HasFactory, SoftDeletes, Sluggable;
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
    protected $dates = ['deleted_at'];
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
    public function projects(): BelongsToMany
    {
        return $this->BelongsToMany(Project::class)->orderBy('created_at','desc')->withTimestamps();
    }

    public function services(): BelongsToMany
    {
        return $this->belongsToMany(Service::class)->withPivot('pause');
    }
    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class)->withTimestamps();
    }
    public function reviews():HasMany
    {
        return $this->hasMany(Review::class)->orderBy('updated_at','desc');
    }

    public function state(){
        return $this->belongsTo(State::class);
    }

    public function getPublicUrlAttribute(){
        return config('app.app_url').'/companies/'.$this->slug;
    }
    public function getReviewRateAttribute(){
        $total = 0;
        $countReviews = $this->reviews->count();
        $sumaReviews = $this->reviews->reduce(function($suma, $review){
            return $suma + $review->rate;
        },0);

        if($countReviews > 0){
            $total = $sumaReviews / $countReviews;
        }

        return floatval(number_format($total, 2));
    }

    public function images()
    {
        return $this->morphMany(Image::class, 'imageable');
    }
}
