<?php

namespace App\Http\Controllers;

use App\Http\Resources\CompanyResource;
use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CompanyController extends Controller
{
    public function list(){
        $companies = Company::all();
        return CompanyResource::collection($companies);
    }

    public function show(Company $company){
        return new CompanyResource($company);
    }
    public function showbySlug($slug){
        $company = Company::where('slug', $slug)->firstOrFail();
        return new CompanyResource($company);
    }

    public function store(Request $request){
        $request->validate([
            'name' => 'required',
            'email' => 'required|email',
            'description' => 'required',
            'phone' => 'required',
            'address_line1' => 'required',
            'city' => 'required',
            'zip_code' => 'required',
            'services' => 'required',
            'states' => 'required',
            'categories' => 'required',
        ]);
        // 'required|mimes:doc,docx,odt,pdf|max:2048'

        $company = Company::create([
            'name' => $request->name,
            'email' => $request->email,
            'description' => $request->description,
            'phone' => $request->phone,
            'address_line1' => $request->address_line1,
            'city' => $request->city,
            'zip_code' => $request->zip_code,
            'video_url' => $request->video_url,
        ]);


        if($request->filled('states')){
            foreach ($request->states as $key => $state) {
                $company->states()->syncWithoutDetaching($state["id"]);
            }
        }

        if($request->filled('services')){
            foreach ($request->services as $key => $service) {
                $company->services()->syncWithoutDetaching($service["id"]);
            }
        }
        if($request->filled('categories')){
            foreach ($request->categories as $key => $category) {
                $company->categories()->syncWithoutDetaching($category["id"]);
            }
        }
        if($request->filled('phone_2')){
            $company->phone_2 = $request->phone_2;
        }
        if($request->filled('phone')){
            $company->phone = $request->phone;
        }

        if($request->filled('address_line2')){
            $company->address_line2 = $request->address_line2;
        }

        if($request->filled('social_facebook')){
            $company->social_facebook = $request->social_facebook;
        }

        if($request->filled('social_x')){
            $company->social_x = $request->social_x;
        }

        if($request->filled('social_youtube')){
            $company->social_youtube = $request->social_youtube;
        }

        if($request->filled('video_url')){
            $company->video_url = $request->video_url;
        }

        $company->save();

        return new Company($company);

    }
    public function storeLogo(Company $company, Request $request){
        $request->validate([
            'image' => 'required'
        ]);
        // 'required|mimes:doc,docx,odt,pdf|max:2048'

        if ($request->hasFile('image')) {
            $image = $request->file('image');

            $nombreArchivo = $company->id.'/logo-'.uniqid() . '.' . $image->extension();;
            Storage::disk('companies')->put($nombreArchivo, file_get_contents($image));
            $urlArchivo = Storage::disk('companies')->url($nombreArchivo);
            $company->logo_url = config('app.api_url').'/'.$urlArchivo;
        }

        $company->save();

        return $company;

    }

    public function update(Company $company, Request $request){
        $request->validate([
            'name' => 'required',
            'email' => 'required|email',
            'description' => 'required',
            'phone' => 'required',
            'address_line1' => 'required',
            'city' => 'required',
            'zip_code' => 'required',
            'services' => 'required',
            'states' => 'required',
            'categories' => 'required',
        ]);
        // 'required|mimes:doc,docx,odt,pdf|max:2048'

        if($request->filled('states')){
            foreach ($request->states as $key => $state) {
                $company->states()->syncWithoutDetaching($state["id"]);
            }
        }

        if($request->filled('services')){
            foreach ($request->services as $key => $service) {
                $company->services()->syncWithoutDetaching($service["id"]);
            }
        }
        if($request->filled('categories')){
            foreach ($request->categories as $key => $category) {
                $company->categories()->syncWithoutDetaching($category["id"]);
            }
        }
        if($request->filled('phone_2')){
            $company->phone_2 = $request->phone_2;
        }
        if($request->filled('phone')){
            $company->phone = $request->phone;
        }

        if($request->filled('address_line2')){
            $company->address_line2 = $request->address_line2;
        }

        if($request->filled('social_facebook')){
            $company->social_facebook = $request->social_facebook;
        }

        if($request->filled('social_x')){
            $company->social_x = $request->social_x;
        }

        if($request->filled('social_youtube')){
            $company->social_youtube = $request->social_youtube;
        }
        if($request->filled('video_url')){
            $company->video_url = $request->video_url;
        }

        $company->save();

        return $company;
    }

    public function destroy(Company $company){
        $company->destroy($company->id);
        return response('Company deleted', 201);
    }
}