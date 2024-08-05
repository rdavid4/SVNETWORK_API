<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Answer extends Model
{
    use HasFactory;

    protected $fillable = [
        'question_id',
        'answer_type_id',
        'comparator_id',
        'meassure_id',
        'order',
        'value_1',
        'value_2',
        'text',
    ];

    public function projects():BelongsToMany
    {
        return $this->belongsToMany(Project::class);
    }
    public function users():BelongsToMany
    {
        return $this->belongsToMany(User::class, 'answer_project', 'answer_id', 'user_id');
    }
    public function question():BelongsTo
    {
        return $this->belongsTo(Question::class);
    }
}
