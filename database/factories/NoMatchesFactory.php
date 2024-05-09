<?php

namespace Database\Factories;

use App\Models\Service;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\NoMatches>
 */
class NoMatchesFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => 1,
            'service_id' => $this->randomService()->id,
            'company_id' => null,
            'project_id' => 1,
            'email' => fake()->freeEmail(),
            'created_at' => fake()->dateTimeBetween('-120 days', 'now')->format('Y-m-d')
        ];
    }

    function randomService(){
        $randomModel = Service::where('id', '<',10 )->inRandomOrder()->first();
        return $randomModel;
    }
}
