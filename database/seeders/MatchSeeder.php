<?php

namespace Database\Seeders;

use App\Models\Matches;
use App\Models\NoMatches;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MatchSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Matches::factory()
        ->count(300)
        ->create();
    }
}
