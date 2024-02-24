<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\State;
use Illuminate\Support\Facades\File;
class StatesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $rutaArchivo = base_path('/database/data/states.json');
        $contenidoJson = File::get($rutaArchivo);
        $states = json_decode($contenidoJson, true);

        foreach ($states['states'] as $key => $state) {

            State::create([
                'name_en' => $state['name_en'],
                'name_es' => $state['name_es'],
                'iso_code' => $state['iso_code'],

            ]);
        }
    }
}
