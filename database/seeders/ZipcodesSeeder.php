<?php

namespace Database\Seeders;

use App\Models\Zipcode;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
class ZipcodesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $rutaArchivo = base_path('/database/data/zipcodes.json');
        $contenidoJson = File::get($rutaArchivo);
        $zipcodes = json_decode($contenidoJson, true);

        foreach ($zipcodes as $key => $zipcode) {

            Zipcode::create([
                'iso' => $zipcode['ISO'],
                'zipcode' => $zipcode['ZIPCODE'],
                'location' => $zipcode['LOCATION'],
                'state' => $zipcode['STATE'],
                'state_iso' => $zipcode['STATE_ISO'],
                'region' => $zipcode['REGION'],
                'id_region' => $zipcode['ID_REGION'],
                'lat' => $zipcode['LAT'],
                'lon' => $zipcode['LON']
            ]);
        }
    }
}
