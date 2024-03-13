<?php

namespace App\Models;

use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Service extends Model
{
    use HasFactory, Sluggable;

    protected $fillable = [
        'name',
        'description',
        'order'
    ];

    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'name'
            ]
        ];
    }

    public function category():BelongsTo
    {
        return $this->BelongsTo(Category::class);
    }
    public function questions():HasMany
    {
        return $this->hasMany(Question::class)->orderBy('id', 'asc');
    }
    public function getQuestionsPaginatedAttribute()
    {
        return $this->questions()->paginate(1);
    }

}
