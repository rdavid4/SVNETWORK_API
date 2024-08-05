<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;
/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class MatchesFactory extends Factory
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
            'company_id' => 1,
            'project_id' => 1,
            'email' => fake()->freeEmail(),
            'created_at' => fake()->dateTimeBetween('-120 days', 'now')->format('Y-m-d')
        ];
    }
}
