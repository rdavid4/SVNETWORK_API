<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            StatesSeeder::class,
            UserSeeder::class,
            CategoriesSeeder::class,
            ServicesSeeder::class,
            ZipcodesSeeder::class,
            CompanySeeder::class,
            MatchSeeder::class,
            NoMatchesSeeder::class,
            ProjectSeeder::class,
            ReviewSeeder::class,
        ]);
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
    }
}
