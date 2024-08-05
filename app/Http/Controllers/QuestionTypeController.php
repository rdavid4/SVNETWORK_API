<?php

namespace App\Http\Controllers;

use App\Models\QuestionType;
use Illuminate\Http\Request;

class QuestionTypeController extends Controller
{
    public function list(){
        $types = QuestionType::all();
        return $types;
    }
}
