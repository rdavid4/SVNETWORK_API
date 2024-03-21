<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::factory()
        ->count(1)
        ->create([
            'email'=>'rogerdavid444@gmail.com',
            'is_admin'=>0,
            'name' => 'Roger',
            'surname' => 'Quinonez',
        ]);

        User::factory()
        ->count(1)
        ->create([
            'email'=>'francisco.hrpros@gmail.com',
            'is_admin'=>1,
            'name' => 'Edenilson',
            'surname' => 'Hernandez',
        ]);

        User::factory()
        ->count(50)
        ->create();
    }
}
