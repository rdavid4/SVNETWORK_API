<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\State;
use Illuminate\Support\Facades\File;
class CategoriesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $rutaArchivo = base_path('/database/data/categories.json');
        $contenidoJson = File::get($rutaArchivo);
        $categories = json_decode($contenidoJson, true);

        foreach ($categories as $key => $category) {

            Category::create([
                'name' => $category['name']
            ]);
        }
    }
}
