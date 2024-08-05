<?php

namespace App\Http\Controllers;

use App\Models\AnswerProject;
use Illuminate\Http\Request;

class AnswerProjectController extends Controller
{
    public function store(Request $request){
        $request->validate([
            'answers'=>'required',
            'project_id'=>'required',
        ]);

        foreach($request->answers as $answer){
            AnswerProject::create([
                'answer_id' => $answer['answer_id'],
                'user_id' => auth()->id(),
                'project_id' => $request->project_id,
                'text' => $answer['text']
            ]);
        }

        return 'ok';
    }
}
