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

        if($request->filled('category_id')){
            $service->category_id = $request->category_id;
        }

        if($request->filled('description')){
            $service->description = $request->description;
            $service->save();
        }

        return ServiceResource::collection(Service::all());
    }

    public function update(Service $service, Request $request){
        $request->validate([
            'name' => 'required'
        ]);

        $service->name = $request->name;

        if($request->filled('category_id')){
            $service->category_id = $request->category_id;
        }

        if($request->filled('description')){
            $service->description = $request->description;
            $service->save();
        }

        return ServiceResource::collection(Service::all());
    }

    public function storePrice(Request $request){
        $request->validate([
            'service_id' => 'required',
            'price' => 'required',
        ]);

        $service = Service::findOrfail($request->service_id);
        $service->price = $request->price;
        $service->save();

        return ServiceResource::collection(Service::all());
    }
    public function destroy(Service $service){
        $service->delete();
        return Service::all();
    }
}
