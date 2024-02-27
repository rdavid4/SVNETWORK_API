<?php

namespace App\Http\Controllers;

use App\Http\Resources\ServiceResource;
use App\Models\Service;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    public function list(){
        $services =  Service::all();
        return ServiceResource::collection($services);
    }
    public function store(Request $request){
        $request->validate([
            'name' => 'required'
        ]);

        $service = Service::create([
            'name' => $request->name
        ]);

        if($request->filled('description')){
            $service->description = $request->description;
            $service->save();
        }

        return ServiceResource::collection(Service::all());
    }
    public function destroy(Service $service){
        $service->delete();
        return Service::all();
    }
}
