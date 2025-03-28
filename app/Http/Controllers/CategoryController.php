<?php

namespace App\Http\Controllers;

use App\Http\Resources\AdminCategoryResource;
use App\Http\Resources\CategoryResource;
use App\Http\Resources\ServiceResource;
use App\Models\Category;
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
}
