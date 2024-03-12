<?php

namespace App\Http\Controllers;

use App\Http\Resources\DashboardServiceResource;
use App\Models\Answer;
use App\Models\Question;
use App\Models\Service;
use Illuminate\Http\Request;

class AnswerController extends Controller
{
    public function store(Request $request){
        $request->validate([
            'question_id' => 'required',
            'value_1' => 'required',
            'text' => 'required'
        ]);
        $question = Question::findOrFail($request->question_id);
        $answer = Answer::create([
            'question_id' =>  $request->question_id,
            'answer_type_id' => $question->type_id,
            'value_1' =>  $request->value_1,
            'text' =>  $request->text
        ]);

        if($request->filled('comparator_id')){
            $answer->comparator_id = $request->comparator_id;
        }

        if($request->filled('meassure_id')){
            $answer->meassure_id = $request->meassure_id;
        }

        if($request->filled('value_2')){
            $answer->value_2 = $request->value_2;
        }

        $answer->save();

        return DashboardServiceResource::collection(Service::all());
    }
}
