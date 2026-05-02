<?php

namespace App\Http\Controllers;

use App\Models\Vessel;
use App\Models\Movement;
use App\Models\ExitGate;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;

class LiveDashboardController extends Controller
{
    public function index()
    {
        $activeVessels = Vessel::where('status', 'inside')->count();
        $outsideVessels = Vessel::where('status', 'outside')->count();
        $recentMovements = Movement::with(['vessel', 'exit', 'user'])
            ->where('moved_at', '>=', Carbon::now()->subHours(1))
            ->orderByDesc('moved_at')
            ->limit(10)
            ->get();

        return view('live-dashboard', [
            'activeVessels' => $activeVessels,
            'outsideVessels' => $outsideVessels,
            'recentMovements' => $recentMovements,
        ]);
    }

    public function getLiveData(): JsonResponse
    {
        $activeVessels = Vessel::where('status', 'inside')->count();
        $outsideVessels = Vessel::where('status', 'outside')->count();
        $totalVessels = Vessel::count();

        // آخر الحركات
        $recentMovements = Movement::with(['vessel', 'exit', 'user'])
            ->where('moved_at', '>=', Carbon::now()->subHours(1))
            ->orderByDesc('moved_at')
            ->limit(15)
            ->get()
            ->map(function ($movement) {
                return [
                    'id' => $movement->id,
                    'vessel' => $movement->vessel->name,
                    'type' => $movement->type === 'exit' ? '🚀 خروج' : '📥 دخول',
                    'type_class' => $movement->type === 'exit' ? 'exit' : 'entry',
                    'exit' => $movement->exit->name,
                    'operator' => $movement->user->name,
                    'time' => $movement->moved_at->diffForHumans(),
                    'timestamp' => $movement->moved_at->format('H:i:s'),
                ];
            });

        // الوسائل النشطة (مع أحدث حركة)
        $activeVesselsList = Vessel::with('latestMovement')
            ->where('status', 'inside')
            ->limit(10)
            ->get()
            ->map(function ($vessel) {
                $latestMove = $vessel->latestMovement;
                return [
                    'id' => $vessel->id,
                    'name' => $vessel->name,
                    'number' => $vessel->vessel_number,
                    'status' => 'نشطة',
                    'last_activity' => $latestMove ? $latestMove->moved_at->diffForHumans() : 'بدون نشاط',
                ];
            });

        // أكثر المخارج استخداماً الآن
        $topExits = Movement::with('exit')
            ->where('moved_at', '>=', Carbon::now()->subHours(1))
            ->groupBy('exit_id')
            ->selectRaw('exit_id, COUNT(*) as count')
            ->orderByDesc('count')
            ->limit(5)
            ->get()
            ->map(function ($item) {
                return [
                    'name' => $item->exit->name,
                    'count' => $item->count,
                ];
            });

        // إحصائيات الساعة الأخيرة
        $hourStats = [
            'total' => Movement::where('moved_at', '>=', Carbon::now()->subHours(1))->count(),
            'exits' => Movement::where('moved_at', '>=', Carbon::now()->subHours(1))
                ->where('type', 'exit')
                ->count(),
            'entries' => Movement::where('moved_at', '>=', Carbon::now()->subHours(1))
                ->where('type', 'entry')
                ->count(),
        ];

        return response()->json([
            'activeVessels' => $activeVessels,
            'outsideVessels' => $outsideVessels,
            'totalVessels' => $totalVessels,
            'occupancyRate' => $totalVessels > 0 ? round(($activeVessels / $totalVessels) * 100, 1) : 0,
            'recentMovements' => $recentMovements,
            'activeVesselsList' => $activeVesselsList,
            'topExits' => $topExits,
            'hourStats' => $hourStats,
            'timestamp' => now()->format('Y-m-d H:i:s'),
        ]);
    }

    public function getMovementUpdates(): JsonResponse
    {
        $lastMinuteMovements = Movement::with(['vessel', 'exit', 'user'])
            ->where('moved_at', '>=', Carbon::now()->subMinute())
            ->orderByDesc('moved_at')
            ->get()
            ->map(function ($movement) {
                return [
                    'id' => $movement->id,
                    'vessel' => $movement->vessel->name,
                    'type' => $movement->type,
                    'exit' => $movement->exit->name,
                    'operator' => $movement->user->name,
                    'message' => $movement->type === 'exit'
                        ? "🚀 خروج الوسيلة {$movement->vessel->name} من {$movement->exit->name}"
                        : "📥 دخول الوسيلة {$movement->vessel->name} إلى {$movement->exit->name}",
                ];
            });

        return response()->json([
            'movements' => $lastMinuteMovements,
            'count' => $lastMinuteMovements->count(),
        ]);
    }
}
