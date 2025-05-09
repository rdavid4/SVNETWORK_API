<?php

namespace App\Http\Controllers;

use App\Models\AnswerProject;
use App\Models\Question;
use Illuminate\Http\Request;

class AnswerProjectController extends Controller
{
    public function store(Request $request){
        $request->validate([
            'answers'=>'required',
            'project_id'=>'required',
        ]);

        foreach($request->answers as $answer){
            $question = Question::find($answer['question_id']);

            AnswerProject::create([
                'answer_id' => $answer['answer_id'] ?? null,
                'user_id' => auth()->id(),
                'project_id' => $request->project_id,
                'question_text' => $question->text,
                'text' => $answer['text']
            ]);
        }

        return 'ok';
    }
}
