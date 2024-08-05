<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Review>
 */
class ReviewFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $user = $this->randomUser();
        $date = fake()->dateTimeBetween('-60 days', 'now')->format('Y-m-d');
        return [
            'description' => fake()->realText($maxNbChars = 150, $indexSize = 2),
            'rate' => rand(1, 5),
            'company_id' => 1,
            'user_id' => $user->id,
            'created_at' => $date,
            'updated_at' => $date
        ];
    }

    public function randomUser(){
        $random = User::inRandomOrder()->first();
        return $random;
    }
}
