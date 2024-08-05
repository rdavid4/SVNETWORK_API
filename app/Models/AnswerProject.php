<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AnswerProject extends Model
{
    use HasFactory;
    protected $table = 'answer_project';
    protected $fillable = [
        'answer_id',
        'user_id',
        'project_id',
        'text'
    ];
}
