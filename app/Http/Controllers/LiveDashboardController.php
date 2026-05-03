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
        $activeVessels = Vessel::active()->where('status', 'inside')->count();
        $outsideVessels = Vessel::active()->where('status', 'outside')->count();
        $archivedVessels = Vessel::archived()->count();
        $operationalVessels = Vessel::active()->where('maintenance_status', 'operational')->count();
        $maintenanceVessels = Vessel::active()->where('maintenance_status', 'maintenance')->count();
        $outOfServiceVessels = Vessel::active()->where('maintenance_status', 'out_of_service')->count();
        $recentMovements = Movement::with(['vessel', 'exit', 'user'])
            ->where('moved_at', '>=', Carbon::now()->subHours(1))
            ->orderByDesc('moved_at')
            ->limit(10)
            ->get();

        return view('live-dashboard', [
            'activeVessels' => $activeVessels,
            'outsideVessels' => $outsideVessels,
            'archivedVessels' => $archivedVessels,
            'operationalVessels' => $operationalVessels,
            'maintenanceVessels' => $maintenanceVessels,
            'outOfServiceVessels' => $outOfServiceVessels,
            'recentMovements' => $recentMovements,
        ]);
    }

    public function getLiveData(): JsonResponse
    {
        $activeVessels = Vessel::active()->where('status', 'inside')->count();
        $outsideVessels = Vessel::active()->where('status', 'outside')->count();
        $totalVessels = Vessel::active()->count();
        $archivedVessels = Vessel::archived()->count();
        $operationalVessels = Vessel::active()->where('maintenance_status', 'operational')->count();
        $maintenanceVessels = Vessel::active()->where('maintenance_status', 'maintenance')->count();
        $outOfServiceVessels = Vessel::active()->where('maintenance_status', 'out_of_service')->count();

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
                    'exit' => $movement->exit?->name ?? 'بدون مخرج',
                    'operator' => $movement->user->name,
                    'time' => $movement->moved_at->diffForHumans(),
                    'timestamp' => $movement->moved_at->format('H:i:s'),
                ];
            });

        // الوسائل النشطة (مع أحدث حركة)
        $activeVesselsList = Vessel::active()->with('latestMovement')
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
            ->where('type', 'exit')
            ->whereNotNull('exit_id')
            ->where('moved_at', '>=', Carbon::now()->subHours(1))
            ->groupBy('exit_id')
            ->selectRaw('exit_id, COUNT(*) as count')
            ->orderByDesc('count')
            ->limit(5)
            ->get()
            ->map(function ($item) {
                return [
                    'name' => $item->exit?->name ?? 'بدون مخرج',
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
            'archivedVessels' => $archivedVessels,
            'operationalVessels' => $operationalVessels,
            'maintenanceVessels' => $maintenanceVessels,
            'outOfServiceVessels' => $outOfServiceVessels,
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
                $exitName = $movement->exit?->name ?? 'بدون مخرج';

                return [
                    'id' => $movement->id,
                    'vessel' => $movement->vessel->name,
                    'type' => $movement->type,
                    'exit' => $exitName,
                    'operator' => $movement->user->name,
                    'message' => $movement->type === 'exit'
                        ? "🚀 خروج الوسيلة {$movement->vessel->name} من {$exitName}"
                        : "📥 دخول الوسيلة {$movement->vessel->name}",
                ];
            });

        return response()->json([
            'movements' => $lastMinuteMovements,
            'count' => $lastMinuteMovements->count(),
        ]);
    }
}
