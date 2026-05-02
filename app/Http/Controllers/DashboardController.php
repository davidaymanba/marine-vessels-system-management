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
        $totalVessels = Vessel::count();
        $outsideCount = Vessel::outside()->count();
        $insideCount = Vessel::inside()->count();

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
            'latestMovements',
            'exitStats',
            'chartLabels',
            'chartValues'
        ));
    }
}
