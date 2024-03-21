<?php

namespace App\Http\Controllers;

use App\Http\Resources\DashboardServicePricesResource;
use App\Http\Resources\DashboardServiceResource;
use App\Http\Resources\ServicePublicResource;
use App\Http\Resources\ServiceResource;
use App\Models\Company;
use App\Models\CompanyService;
use App\Models\Service;
use Illuminate\Http\Request;
use App\Models\Zipcode;
use App\Models\CompanyServiceZip;
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

    public function zipcodesByRegion(Request $request){
        $request->validate([
            'region' => 'required',
            'state_iso' => 'required',
            'service_id' => 'required',
            'company_id' => 'required'
        ]);

        $zipcodes = Zipcode::where('region', $request->region)->where('state_iso', $request->state_iso)->get();
        $serviceZipCodes = CompanyServiceZip::where('service_id', $request->service_id)
        ->where('company_id', $request->company_id)
        ->get();
        $serviceZipCodes = $serviceZipCodes->map(function($zipcodes){
            return $zipcodes->zipcode_id;
        });

        $zipcodes = $zipcodes->map(function($zip) use($serviceZipCodes){
            if(in_array($zip->id, $serviceZipCodes->toArray())){
                $zip->active = true;
            }else{
                $zip->active = false;
            }

            return $zip;

        });

        return $zipcodes;
    }

    public function updateZipcodes(Request $request){
        $request->validate([
            'company_id' => 'required',
            'service_id' => 'required',
            'region' => 'required',
        ]);

        $service = Service::find($request->service_id);
        if(isset($request->zipcodes)){
            $zipcodes = array_map(function($zip){
                return $zip['id'];
            }, $request->zipcodes);

            $service->companyServiceZip()
            ->where('region_text',$request->region)
            ->where('company_id', $request->company_id)
            ->delete();

            foreach ($zipcodes as $key => $zip) {
                # code...
                $service->zipcodes()->syncWithoutDetaching([
                    $zip => ['company_id' => $request->company_id, 'region_text' => $request->region,'active' => true]
                ]);
            }
        }
        return  $zipcodes;
    }
    public function pause(Request $request){
        $request->validate([
            'service_id' => 'required',
            'company_id' => 'required',
            'pause' => 'required',
        ]);

        $company = Company::find($request->company_id);
        $service = $company->services->where('id', $request->service_id)->first();
        $serviceCompany = CompanyService::where('company_id', $company->id)->where('service_id', $service->id)->first();
        $serviceCompany->pause = $request->pause;
        $serviceCompany->save();

        return $serviceCompany;

    }
}
