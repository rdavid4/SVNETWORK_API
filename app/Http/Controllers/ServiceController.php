<?php

namespace App\Http\Controllers;

use App\Http\Resources\DashboardCompanyResource;
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
use App\Models\Project;
use App\Models\State;
use Illuminate\Support\Facades\Storage;

class ServiceController extends Controller
{
    public function list(){
        $services =  Service::all();
        return ServiceResource::collection($services);
    }
    public function top10(Request $request){
        $zipcode = $request->query('zipcode');
        $zipcode = Zipcode::where('zipcode', $zipcode)->first();
        $results = collect([]);

        //Zipcode == project
        $projectsZip = Project::where('zipcode_id', $zipcode?->id)
            ->orderBy('id', 'desc')
            ->get();

        $results = $results->concat($projectsZip->pluck('service_id'))->unique();

        //State iso == project
        if($results->count() < 10){
            $stateZipcodes = Zipcode::where('state_iso', $zipcode?->state_iso)->get()->pluck('id');
            $projectsState = Project::whereIn('zipcode_id', $stateZipcodes)->whereNotIn('id', $projectsZip->pluck('id'))
            ->orderBy('id', 'desc')
            ->get();
            $results = $results->concat($projectsState->pluck('service_id'))->unique();
        }

        // Any project
        if($results->count() < 10){
            $projectsAll = Project::whereNotIn('id', $projectsZip->pluck('id'))->whereNotIn('id', $projectsState->pluck('id'))
            ->orderBy('id', 'desc')
            ->get();
            $results = $results->concat($projectsAll->pluck('service_id'))->unique();
        }

        //Any Service
        if($results->count() < 10){
            $servicesAll = Service::whereNotIn('id', $results)->orderBy('id', 'desc')->get();
            $results = $results->concat($servicesAll->pluck('id'))->unique();
        }

        $results = $results->values();
        $services = $results->map(function($service){
            return Service::find($service);
        })->filter(function($service) {
            return !is_null($service);
        })->values();

        return ServiceResource::collection($services->take(8));
    }
    public function prices(){
        $services = Service::all();
        return DashboardServicePricesResource::collection($services);
    }
    public function addService(Request $request){
        $request->validate([
            'company_id' => 'required',
            'service' => 'required',
        ]);

        $company = Company::find($request->company_id);
        $service = Service::find($request->service);
                $company->services()->syncWithoutDetaching([
                    $request->service => [
                        'pause' => 1
                    ]
                ]);

        return new DashboardCompanyResource($company);
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

    public function removeService(Request $request){
        $request->validate([
            'company_id' => 'required',
            'service_id' => 'required',
        ]);

        $company = Company::find($request->company_id);
        $service = Service::find($request->service_id);
                $company->services()->where('service_id', $service->id)->delete();

        return new DashboardCompanyResource($company);
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
        ->where('company_id', $request->company_id)->where('state_iso', $request->state_iso)
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
            $service->companyServiceZip()
            ->where('region_text',$request->region)
            ->where('company_id', $request->company_id)
            ->delete();

            $zipcodes = array_map(function($zip)use($service, $request){
                $service->zipcodes()->syncWithoutDetaching([
                    $zip['id'] => ['company_id' => $request->company_id, 'region_text' => $request->region,'active' => true, 'state_iso' => $zip['state_iso']]
                ]);
            }, $request->zipcodes);



            // foreach ($zipcodes as $key => $zip) {
            //     # code...
            //     $service->zipcodes()->syncWithoutDetaching([
            //         $zip => ['company_id' => $request->company_id, 'region_text' => $request->region,'active' => true]
            //     ]);
            // }
        }
        return  $zipcodes;
    }

    public function selectAllState(Request $request){
        $request->validate([
            'company_id' => 'required',
            'service_id' => 'required',
            'state_id' => 'required',
        ]);

        $service = Service::find($request->service_id);
        $state = State::find($request->state_id);
        $zipcodes = Zipcode::where('state_iso', $state->iso_code)->get();
        foreach ($zipcodes as $key => $zip) {
            # code...
            $service->zipcodes()->syncWithoutDetaching([
                $zip->id => ['company_id' => $request->company_id, 'region_text' => $zip->region,'active' => true, 'state_iso' => $state->iso_code]
            ]);
        }

        return response()->json([
            'Updated successfully'
        ]);

    }
    public function removeAllState(Request $request){
        $request->validate([
            'company_id' => 'required',
            'service_id' => 'required',
            'state_id' => 'required',
        ]);

        $service = Service::find($request->service_id);
        $state = State::find($request->state_id);
        $zipcodes = Zipcode::where('state_iso', $state->iso_code)->get();
        foreach ($zipcodes as $key => $zip) {
            # code...
            $service->zipcodes()->detach(
                $zip->id
            );
        }

        return response()->json([
            'Updated successfully'
        ]);

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

    public function storeImage(Service $service, Request $request)
    {
        $request->validate([
            'image' => 'required'
        ]);
        // 'required|mimes:doc,docx,odt,pdf|max:2048'

        if ($request->hasFile('image')) {
            $image = $request->file('image');

            $nombreArchivo = $service->id . '/image-' . uniqid() . '.' . $image->extension();;
            Storage::disk('services')->put($nombreArchivo, file_get_contents($image));
            $urlArchivo = Storage::disk('services')->url($nombreArchivo);
            $service->image = $urlArchivo;
        }

        $service->save();

        return $service;
    }
}
