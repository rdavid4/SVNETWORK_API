<?php

namespace App\Http\Controllers;

use App\Http\Resources\DashboardUserResource;
use App\Http\Resources\DashboardUsersResource;
use App\Models\Company;
use App\Models\Matches;
use App\Models\NoMatches;
use App\Models\Project;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Service;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function registeredUsers()
    {
        $dates = User::selectRaw("DATE_FORMAT(created_at, '%b %d %Y') as date")
            ->groupBy('date')
            ->orderBy('date', 'asc')
            ->pluck('date');
        $totals = User::selectRaw('DATE(created_at) as date, COUNT(*) as total')
            ->groupBy('date')
            ->orderBy('date', 'asc')
            ->pluck('total');
        $data = [
            'categories' => $dates,
            'series' => $totals
        ];
        return $data;
    }

    public function registeredCompanies()
    {
        $dates = Company::selectRaw("DATE_FORMAT(created_at, '%b %d %Y') as date")
            ->groupBy('date')
            ->orderBy('date', 'asc')
            ->pluck('date');
        $totals = Company::selectRaw('DATE(created_at) as date, COUNT(*) as total')
            ->groupBy('date')
            ->orderBy('date', 'asc')
            ->pluck('total');
        $data = [
            'categories' => $dates,
            'series' => $totals
        ];
        return $data;
    }

    public function topServices()
    {
        $matchesPorMes = Project::selectRaw('DATE_FORMAT(created_at, "%M-%Y") as mes')
        ->distinct()
        ->orderByRaw('YEAR(created_at), MONTH(created_at)')
        ->get();

        $meses = $matchesPorMes->pluck('mes')->toArray();

        $matchesPorServicioYMes = Project::selectRaw('service_id, MONTH(created_at) as mes, count(service_id) as total')
            ->groupBy('service_id', DB::raw('MONTH(created_at)'))
            ->get();

        $resultados = [];
        foreach ($matchesPorServicioYMes as $match) {
            $service = Service::withTrashed()->find($match->service_id)?->name;
            $total = $match->total;

                if (!isset($resultados[$service])) {
                    $resultados[$service] = ['name' => $service];
                }
                $resultados[$service]['data'][] = $total;

        }

        // Convertir el array asociativo en un array simple
        $resultados = array_values($resultados);
        return ['series' => $resultados, 'categories' => $meses];
    }

    public function totalMatches()
    {
        $dates = Matches::selectRaw("DATE_FORMAT(created_at, '%b %d %Y') as date")
            ->groupBy('date')
            ->orderBy('date', 'asc')
            ->pluck('date');
        $totals = Matches::selectRaw('DATE(created_at) as date, COUNT(*) as total')
            ->groupBy('date')
            ->orderBy('date', 'asc')
            ->pluck('total');
        $data = [
            'categories' => $dates,
            'series' => $totals
        ];
        return $data;
    }
    public function totalNomatches()
    {
        $dates = NoMatches::selectRaw("DATE_FORMAT(created_at, '%b %d %Y') as date")
            ->groupBy('date')
            ->orderBy('date', 'asc')
            ->pluck('date');
        $totals = NoMatches::selectRaw('DATE(created_at) as date, COUNT(*) as total')
            ->groupBy('date')
            ->orderBy('date', 'asc')
            ->pluck('total');
        $data = [
            'categories' => $dates,
            'series' => $totals
        ];
        return $data;
    }

    public function getStats()
    {
        $users = User::whereNotNull('password')->where('is_admin', 0)->count();
        $companies = Company::all()->count();
        $matches = Matches::all()->count();
        $noMatches = NoMatches::all()->count();

        $data = [
            'users' => $users,
            'companies' => $companies,
            'matches' => $matches,
            'noMatches' => $noMatches,
        ];

        return $data;
    }
}
