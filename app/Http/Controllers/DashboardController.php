<?php

namespace App\Http\Controllers;

use App\Models\ExitGate;
use App\Models\Movement;
use App\Models\Vessel;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $totalVessels = Vessel::active()->count();
        $outsideCount = Vessel::active()->outside()->count();
        $insideCount = Vessel::active()->inside()->count();
        $archivedCount = Vessel::archived()->count();
        $operationalCount = Vessel::active()->where('maintenance_status', 'operational')->count();
        $maintenanceCount = Vessel::active()->where('maintenance_status', 'maintenance')->count();
        $outOfServiceCount = Vessel::active()->where('maintenance_status', 'out_of_service')->count();

        $latestMovements = Movement::with(['vessel', 'exit', 'user'])
            ->whereDate('moved_at', Carbon::today())
            ->orderByDesc('moved_at')
            ->limit(10)
            ->get();

        $exitStats = ExitGate::query()
            ->where('is_active', true)
            ->withCount(['movements as movements_count' => function ($query) {
                $query->whereDate('moved_at', Carbon::today());
            }])
            ->get();

        $chartLabels = $exitStats->pluck('name')->values();
        $chartValues = $exitStats->pluck('movements_count')->values();

        return view('dashboard', compact(
            'totalVessels',
            'outsideCount',
            'insideCount',
            'archivedCount',
            'operationalCount',
            'maintenanceCount',
            'outOfServiceCount',
            'latestMovements',
            'exitStats',
            'chartLabels',
            'chartValues'
        ));
    }
}
