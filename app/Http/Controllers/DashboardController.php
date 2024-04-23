<?php

namespace App\Http\Controllers;

use App\Http\Resources\DashboardUserResource;
use App\Http\Resources\DashboardUsersResource;
use App\Models\Company;
use App\Models\Matches;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Carbon;
class DashboardController extends Controller
{
    public function registeredUsers(){
        $dates = User::selectRaw("DATE_FORMAT(created_at, '%b %d %Y') as date")
        ->groupBy('date')
        ->orderBy('date', 'desc')
        ->pluck('date');
        $totals = User::selectRaw('DATE(created_at) as date, COUNT(*) as total')
               ->groupBy('date')
               ->orderBy('date', 'desc')
               ->pluck('total');
        $data = [
            'categories' => $dates,
            'series' => $totals
        ];
        return $data;
    }

    public function registeredCompanies(){
        $dates = Company::selectRaw("DATE_FORMAT(created_at, '%b %d %Y') as date")
        ->groupBy('date')
        ->orderBy('date', 'desc')
        ->pluck('date');
        $totals = Company::selectRaw('DATE(created_at) as date, COUNT(*) as total')
               ->groupBy('date')
               ->orderBy('date', 'desc')
               ->pluck('total');
        $data = [
            'categories' => $dates,
            'series' => $totals
        ];
        return $data;
    }

    public function totalMatches(){
        $dates = Matches::selectRaw("DATE_FORMAT(created_at, '%b %d %Y') as date")
        ->groupBy('date')
        ->orderBy('date', 'desc')
        ->pluck('date');
        $totals = Matches::selectRaw('DATE(created_at) as date, COUNT(*) as total')
               ->groupBy('date')
               ->orderBy('date', 'desc')
               ->pluck('total');
        $data = [
            'categories' => $dates,
            'series' => $totals
        ];
        return $data;
    }

    public function getStats(){
        $users = User::whereNotNull('password')->where('is_admin', 0)->count();
        $companies = Company::all()->count();
        $matches = Matches::all()->count();

        $data = [
            'users' => $users,
            'companies' => $companies,
            'matches' => $matches,
        ];

        return $data;
    }
}
