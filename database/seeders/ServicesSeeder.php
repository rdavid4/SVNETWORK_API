<?php

namespace Database\Seeders;

use App\Models\Service;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
class ServicesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $rutaArchivo = base_path('/database/data/services.json');
        $contenidoJson = File::get($rutaArchivo);
        $services = json_decode($contenidoJson, true);

        foreach ($services as $key => $service) {
            
            Service::create([
                'name' => $service['name'],
                'category_id' => $service['category_id'],


            ]);
        }
    }
}
