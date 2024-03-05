<?php

namespace App\Http\Controllers;

use App\Models\Question;
use Illuminate\Http\Request;

class QuestionController extends Controller
{
    public function store(Request $request){
        $request->validate([
            'question' => 'required',
            'type_id' => 'required',
        ]);

        $question = Question::create([
            'question' => $request->question,
            'type_id' => $request->type_id
        ]);

        return $question;
    }

    public function update(Request $request){

    }
}
