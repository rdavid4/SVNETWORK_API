<?php

namespace Database\Factories;

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
            'service_id' => 1,
            'company_id' => null,
            'project_id' => 1,
            'email' => fake()->freeEmail(),
            'created_at' => fake()->dateTimeBetween('-120 days', 'now')->format('Y-m-d')
        ];
    }
}
