<?php

namespace App\Http\Controllers;

use App\Http\Resources\ProjectResource;
use App\Http\Resources\UserProjectResource;
use App\Models\AnswerProject;
use App\Models\Project;
use App\Models\Service;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Zipcode;
use Illuminate\Support\Facades\Storage;

class ProjectController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required',
            'details' => 'required',
            'service_id' => 'required',
            'zipcode_id' => 'required'
        ]);

        $user = User::findOrfail($request->user_id);
        $service = Service::find($request->service_id);
        $zipcode = Zipcode::find($request->zipcode_id);
        $title = $service->name . ' in ' . $zipcode->location . ', ' . $zipcode->state . ' ' . $zipcode->zipcode;

        $project = $user->projects()->create([
            'description' => $request->details,
            'service_id' => $request->service_id,
            'title' => $title,
            'zipcode_id' => $request->zipcode_id,
        ]);
        return $project;
    }
    public function storeImage(Request $request)
    {
        $request->validate([
            'project_id' => 'required',
            'user_id' => 'required',
            'image' => 'required'
        ]);

        $user = User::findOrfail($request->user_id);
        $project = Project::find($request->project_id);

        //Save image
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $filename = $project->uuid . '/image-' . uniqid() . '.' . $image->extension();
            Storage::disk('projects')->put($filename, file_get_contents($image));
            $extension = $image->extension();
            $size = $image->getSize();
            $mimetype = $image->getMimeType();

            $image = $project->images()->create([
                'filename' => $filename,
                'mime_type' => $mimetype,
                'extension' => $extension,
                'size' => $size
            ]);

            return $image;
        }
    }

    public function show(Project $project){
        $openAnswers = AnswerProject::where('project_id', $project->id)->whereNull('answer_id')->get();
        $project->openAnswers = $openAnswers;
        return new ProjectResource($project);
    }
}
