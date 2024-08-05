<?php

namespace Database\Factories;

use App\Models\State;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use Cviebrock\EloquentSluggable\Sluggable;
/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Company>
 */
class CompanyFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {

        return [
            'name' => fake()->company(),
            'slug' => fake()->slug(),
            'uuid' => Str::uuid(),
            'email' => fake()->unique()->safeEmail(),
            'description' => fake()->realText(),
            'state_id' => $this->getState(),
            'phone' => fake()->phoneNumber(),
            'phone_2' => fake()->phoneNumber(),
            'address_line1' => fake()->address(),
            'address_line2' => fake()->address(),
            'social_facebook' => fake()->freeEmail(),
            'social_x' => fake()->freeEmail(),
            'social_youtube' => fake()->freeEmail(),
            'country_id' => 1,
            'city' => fake()->city(),
            'zip_code' => fake()->postcode(),
            'video_url' => fake()->imageUrl(),
            'logo_url' => fake()->imageUrl(),
            'created_at' => fake()->dateTimeBetween('-60 days', 'now')->format('Y-m-d')
        ];

    }

    function getState(){
        $state =  State::inRandomOrder()->first();
        return $state->id;
    }
}
