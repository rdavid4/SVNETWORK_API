<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
}
