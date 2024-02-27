<?php

namespace App\Http\Controllers;

use App\Http\Resources\CategoryResource;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function list(){
        $categories = Category::all();
        return CategoryResource::collection($categories);
    }
    public function store(Request $request){
        $request->validate([
            'name' => 'required',
            'category_id' => 'required'
        ]);

        $service = Category::create([
            'name' => $request->name,
            'category_id' => $request->category_id,
        ]);

        if($request->filled('description')){
            $service->description = $request->description;
            $service->save();
        }

        return Category::all();
    }
    public function destroy(Category $service){
        $service->delete();
        return Category::all();
    }
}
