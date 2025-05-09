<?php

namespace App\Http\Controllers;

use App\Http\Resources\AdminCategoryResource;
use App\Http\Resources\CategoryResource;
use App\Http\Resources\CompanyResource;
use App\Http\Resources\CompanySearchResource;
use App\Http\Resources\ServiceResource;
use App\Models\Category;
use App\Models\Company;
use App\Models\CompanyService;
use App\Models\CompanyServiceZip;
use App\Models\Zipcode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CategoryController extends Controller
{
    public function list(){
        $categories = Category::all();
        return CategoryResource::collection($categories);
    }
    public function adminList(){
        $categories = Category::all();
        return AdminCategoryResource::collection($categories);
    }
    public function getBySlug($slug){
        $category = Category::where('slug', $slug)->first();
        return new CategoryResource($category);
    }
    public function getServicesBySlug($slug){
        $category = Category::where('slug', $slug)->first();
        return ServiceResource::collection($category->services);
    }
    public function getServicesListBySlug($slug){
        $category = Category::where('slug', $slug)->first();
        $servicesList = $category->services->pluck('name');
        return $servicesList;
    }
    public function getCompaniesByCategory($slug, Request $request){
        #Rules
        #Debe tener un servicio en dicha categoria
        #El servicio debe estar activo
        #El servicio debe estar relacionado con el zipcode
        #Companies deben estar verificados
        #Usuarios de la compania debe tener cuenta activa en stripe
        $zipcode = null;
        $iso_code = null;
        if($request->query('zipcode')){
            $zipcode = Zipcode::where('zipcode', $request->query('zipcode'))->first();
        }
        if($request->query('iso_code')){
            $iso_code = $request->query('iso_code');
        }
        $category = Category::where('slug', $slug)->first();
        $servicesBycategory = $category->services->pluck('id');
        $companyService = CompanyServiceZip::whereIn('service_id', $servicesBycategory)->where('zipcode_id', $zipcode->id ?? null)
        ->where('active', 1)
        ->pluck('company_id')
        ->unique()
        ->values();

        $companyServiceByState = CompanyServiceZip::whereIn('service_id', $servicesBycategory)->where('state_iso', $iso_code)
        ->where('active', 1)
        ->pluck('company_id')
        ->unique()
        ->values();

        $companies = Company::whereIn('id', $companyService)->where('verified', 1)->orderBy('created_at','DESC')->get();
        $companiesBystate = Company::whereIn('id', $companyServiceByState)->where('verified', 1)->whereNotIn('id',$companyService)->orderBy('created_at','DESC')->get();
        $companiesBycategory =  Company::whereHas('services', function ($query) use ($category) {
            $query->where('category_id', $category->id);
        })->whereNotIn('id',$companyService)->where('verified', 1)->orderBy('created_at','DESC')->get();


        $companiesList = $companies->map(function ($company)use($category) {
            $company->services = $company->getServicesByCategory($category->id);
            return $company;
        });

        $companiesBystateList = $companiesBystate->map(function ($company)use($category) {
            $company->services = $company->getServicesByCategory($category->id);
            return $company;
        });

        $companiesBycategoryList = $companiesBycategory->map(function ($company)use($category) {
            $company->services = $company->getServicesByCategory($category->id);
            return $company;
        });

        return [
            'companies' => CompanySearchResource::collection($companiesList),
            'companies_by_state' => CompanySearchResource::collection($companiesBystateList),
            'companies_by_category' => CompanySearchResource::collection($companiesBycategoryList),
            'category' => new CategoryResource($category)
        ];
    }
    public function store(Request $request){

        $request->validate([
            'name' => 'required'
        ]);

        $service = Category::firstOrCreate([
            'name' => $request->name
        ]);


        return CategoryResource::collection(Category::all());

    }
    public function update(Category $category, Request $request){

        if($request->filled('title')){
            $category->title = $request->title;
            $category->save();
        }
        if($request->filled('description')){
            $category->description = $request->description;
            $category->save();
        }
        if($request->filled('meta_title')){
            $category->meta_title = $request->meta_title;
            $category->save();
        }
        if($request->filled('meta_description')){
            $category->meta_description = $request->meta_description;
            $category->save();
        }
        if($request->filled('meta_title_2')){
            $category->meta_title_2 = $request->meta_title_2;
            $category->save();
        }
        if($request->filled('meta_description2')){
            $category->meta_description2 = $request->meta_description2;
            $category->save();
        }

        return CategoryResource::collection(Category::all());

    }
    public function destroy(Category $service){
        $service->delete();
        return Category::all();
    }

    public function storeImage(Category $category, Request $request)
    {
        $request->validate([
            'image' => 'required'
        ]);
        // 'required|mimes:doc,docx,odt,pdf|max:2048'

        if ($request->hasFile('image')) {
            $image = $request->file('image');

            $nombreArchivo = $category->id . '/image-' . uniqid() . '.' . $image->extension();;
            Storage::disk('categories')->put($nombreArchivo, file_get_contents($image));
            $urlArchivo = Storage::disk('categories')->url($nombreArchivo);
            $category->image = $urlArchivo;
        }

        $category->save();

        return $category;
    }
    public function storeIcon(Category $category, Request $request)
    {
        $request->validate([
            'icon' => 'required'
        ]);
        // 'required|mimes:doc,docx,odt,pdf|max:2048'

        if ($request->hasFile('icon')) {
            $icon = $request->file('icon');

            $fileName = $category->id . '/icon-' . uniqid() . '.' . 'svg';
            Storage::disk('categories')->put($fileName, file_get_contents($icon));
            $urlArchivo = Storage::disk('categories')->url($fileName);
            $category->icon = $urlArchivo;
        }

        $category->save();

        return $category;
    }
}
