<?php

namespace App\Http\Controllers;

use App\Http\Resources\DashboardServicePricesResource;
use App\Http\Resources\DashboardServiceResource;
use App\Http\Resources\ServicePublicResource;
use App\Http\Resources\ServiceResource;
use App\Models\Service;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    public function list(){
        $services =  Service::all();
        return ServiceResource::collection($services);
    }

    public function prices(){
        $services = Service::all();
        return DashboardServicePricesResource::collection($services);
    }

    public function store(Request $request){
        $request->validate([
            'name' => 'required'
        ]);

        $service = Service::firstOrCreate([
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
        if($request->filled('price')){
            $service->price = $request->price;
        }

        if($request->filled('description')){
            $service->description = $request->description;
        }

        $service->save();

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
        return DashboardServiceResource::collection(Service::all());
    }
    public function show(Service $service){
       $service = new DashboardServiceResource($service);
        return $service;
    }
    public function showPublic(Service $service){
       $service = new ServicePublicResource($service);
        return $service;
    }
}
