<?php

namespace App\Http\Controllers;

use App\Http\Resources\DashboardServiceResource;
use App\Models\Question;
use App\Models\Service;
use Illuminate\Http\Request;

class QuestionController extends Controller
{
    public function store(Request $request){
        $request->validate([
            'text' => 'required',
            'service_id' => 'required',
            'type_id' => 'required',
        ]);

        $service = Service::findOrfail($request->service_id);
        Question::create([
            'type_id' => $request->type_id,
            'service_id' => $request->service_id,
            'text' => $request->text
        ]);

        return DashboardServiceResource::collection(Service::all());
    }
    public function destroy(Question $question){
        $question->delete();
        $question->answers()->delete();
        return DashboardServiceResource::collection(Service::all());
    }

    public function update(Request $request){

    }
}
