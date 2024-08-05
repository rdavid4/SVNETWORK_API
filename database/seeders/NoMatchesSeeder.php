<?php

namespace Database\Seeders;

use App\Models\NoMatches;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class NoMatchesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        NoMatches::factory()
        ->count(50)
        ->create();
    }
}
