<?php

namespace Database\Seeders;

use App\Models\Company;
use App\Models\Service;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;

class CompanySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::where('email', 'rogerdavid444@gmail.com')->first();
        Company::factory()
            ->count(1)
            ->create(['name' => 'Qsoftcom']);

        $company = Company::where('name', 'Qsoftcom')->first();
        $company->users()->syncWithoutDetaching($user->id);
        Company::factory()
            ->count(50)
            ->create();
    }
}
