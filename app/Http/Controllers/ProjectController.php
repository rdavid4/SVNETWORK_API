<?php

namespace App\Http\Controllers;

use App\Http\Resources\ProjectResource;
use App\Http\Resources\UserDataResource;
use App\Http\Resources\UserProjectResource;
use App\Models\AnswerProject;
use App\Models\Matches;
use App\Models\Project;
use App\Models\Service;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Zipcode;
use DateTime;
use Illuminate\Support\Facades\Date;
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
            'state_iso' => $zipcode->state_iso
        ]);
        return $project;
    }

    public function storeImage(Request $request)
    {
        $request->validate([
            'project_id' => 'required',
            'image' => 'required'
        ]);


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
                'type' => 1,
                'size' => $size
            ]);

            return $image;
        }
    }

    public function show(Project $project)
    {
        $openAnswers = AnswerProject::where('project_id', $project->id)->whereNull('answer_id')->get();
        $project->openAnswers = $openAnswers;
        $user = auth()->user();
        $userCompaniesIds = $user->companies->pluck('id');
        $match = $project->matches->whereIn('company_id', $userCompaniesIds)->first();
        if (!$match) {
            abort(403);
        }
        $project->show_contact = $match->show_contact;
        return new ProjectResource($project);
    }
    public function showContact(Matches $match)
    {
        $user = auth()->user();
        $company = $match->company;
        if ($company->users->whereIn('id', $user->id)->count() == 0) {
            abort(403);
        }

        $match->show_contact = date('Y-m-d H:i:s');
        $match->save();
        if ($match->client) {
            return new UserDataResource($match->client);
        } else {
            return null;
        }
    }
    public function showContactCheck(Matches $match)
    {
        $user = auth()->user();
        $company = $match->company;
        if ($company->users->whereIn('id', $user->id)->count() == 0) {
            abort(403);
        }

        if ($match->show_contact) {
            if ($match->client) {
                return new UserDataResource($match->client);
            }
        } else {
            return null;
        }
    }
}
