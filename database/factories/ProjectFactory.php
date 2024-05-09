<?php

namespace Database\Factories;

use App\Models\Service;
use App\Models\Zipcode;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Project>
 */
class ProjectFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $zip = $this->randomZip();
        $service = $this->randomService();
        $title = $service->nam.' in '.$zip->state.' '.$zip->zipcode;
        return [
            'title' => $title,
            'description' => fake()->realText($maxNbChars = 200),
            'user_id' => 1,
            'zipcode_id' => $zip->id,
            'service_id' => $service->id,
            'created_at' => fake()->dateTimeBetween('-120 days', 'now')->format('Y-m-d')
        ];
    }

    function randomZip(){
        $randomModel = Zipcode::inRandomOrder()->first();
        return $randomModel;
    }
    function randomService(){
        $randomModel = Service::where('id', '<',10 )->inRandomOrder()->first();
        return $randomModel;
    }
}
