<?php

namespace App\Policies;

use App\Models\Company;
use App\Models\Image;
use App\Models\User;

class CompanyPolicy
{
    /**
     * Create a new policy instance.
     */
    public function __construct()
    {

    }

    public function update(User $user, Company $company)
    {
        return $user->companies->pluck('id')->contains($company->id);
    }

    public function deleteImage(User $user, Company $company)
    {
        return $user->companies->pluck('id')->contains($company->id);
    }
}
