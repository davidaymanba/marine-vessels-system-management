<?php

namespace App\Http\Controllers;

use App\Exports\ReportsExport;
use App\Models\ExitGate;
use App\Models\Movement;
use App\Models\Vessel;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;
use OpenSpout\Common\Entity\Row;
use OpenSpout\Writer\Common\Creator\WriterEntityFactory;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class ReportController extends Controller
{
    public function index()
    {
        $vessels = Vessel::orderBy('name')->get();
        $exits = ExitGate::orderBy('name')->get();

        return view('reports.index', compact('vessels', 'exits'));
    }

    public function daily()
    {
        $start = Carbon::today();
        $end = Carbon::today()->endOfDay();

        return $this->renderReport('التقرير اليومي', $start, $end, route('reports.pdf', ['scope' => 'daily']), route('reports.excel', ['scope' => 'daily']));
    }

    public function weekly()
    {
        $start = Carbon::now()->startOfWeek();
        $end = Carbon::now()->endOfWeek();

        return $this->renderReport('التقرير الأسبوعي', $start, $end, route('reports.pdf', ['scope' => 'weekly']), route('reports.excel', ['scope' => 'weekly']));
    }

    public function monthly()
    {
        $start = Carbon::now()->startOfMonth();
        $end = Carbon::now()->endOfMonth();

        return $this->renderReport('التقرير الشهري', $start, $end, route('reports.pdf', ['scope' => 'monthly']), route('reports.excel', ['scope' => 'monthly']));
    }

    public function custom(Request $request)
    {
        $validated = $request->validate([
            'date_from' => ['required', 'date'],
            'date_to' => ['required', 'date', 'after_or_equal:date_from'],
        ]);

        $start = Carbon::parse($validated['date_from'])->startOfDay();
        $end = Carbon::parse($validated['date_to'])->endOfDay();

        return $this->renderReport('تقرير مخصص', $start, $end, route('reports.pdf', [
            'scope' => 'custom',
            'date_from' => $validated['date_from'],
            'date_to' => $validated['date_to'],
        ]), route('reports.excel', [
            'scope' => 'custom',
            'date_from' => $validated['date_from'],
            'date_to' => $validated['date_to'],
        ]));
    }

    public function byVessel(string $id)
    {
        $vessel = Vessel::findOrFail($id);
        $query = Movement::with(['vessel', 'exit', 'user'])
            ->where('vessel_id', $vessel->id)
            ->orderByDesc('moved_at');

        return $this->renderReport('تقرير وسيلة: ' . $vessel->name, null, null, route('reports.pdf', [
            'scope' => 'vessel',
            'id' => $vessel->id,
        ]), route('reports.excel', [
            'scope' => 'vessel',
            'id' => $vessel->id,
        ]), $query);
    }

    public function byExit(string $id)
    {
        $exit = ExitGate::findOrFail($id);
        $query = Movement::with(['vessel', 'exit', 'user'])
            ->where('exit_id', $exit->id)
            ->orderByDesc('moved_at');

        return $this->renderReport('تقرير مخرج: ' . $exit->name, null, null, route('reports.pdf', [
            'scope' => 'exit',
            'id' => $exit->id,
        ]), route('reports.excel', [
            'scope' => 'exit',
            'id' => $exit->id,
        ]), $query);
    }

    public function downloadPdf(Request $request)
    {
        $validated = $request->validate([
            'scope' => ['required', 'in:daily,weekly,monthly,custom,vessel,exit'],
            'date_from' => ['nullable', 'date'],
            'date_to' => ['nullable', 'date'],
            'id' => ['nullable', 'integer'],
        ]);

        [$title, $data] = $this->buildReportData($validated);

        $pdf = Pdf::loadView('reports.pdf', $data)->setPaper('a4', 'portrait');

        return $pdf->download(str()->slug($title) . '.pdf');
    }

    public function downloadExcel(Request $request): BinaryFileResponse
    {
        $validated = $request->validate([
            'scope' => ['required', 'in:daily,weekly,monthly,custom,vessel,exit'],
            'date_from' => ['nullable', 'date'],
            'date_to' => ['nullable', 'date'],
            'id' => ['nullable', 'integer'],
        ]);

        [$title, $data] = ReportsExport::resolve($validated['scope'], $validated);
        $rows = ReportsExport::rows($data);

        $filePath = tempnam(sys_get_temp_dir(), 'marine_report_') . '.xlsx';
        $writer = WriterEntityFactory::createXLSXWriter();
        $writer->openToFile($filePath);

        foreach ($rows as $row) {
            $writer->addRow(WriterEntityFactory::createRowFromArray($row));
        }

        $writer->close();

        return response()
            ->download($filePath, str()->slug($title) . '.xlsx')
            ->deleteFileAfterSend(true);
    }

    private function renderReport(string $title, ?Carbon $start, ?Carbon $end, ?string $pdfUrl = null, ?string $excelUrl = null, ?Builder $baseQuery = null)
    {
        $query = $baseQuery ?? Movement::with(['vessel', 'exit', 'user'])
            ->orderByDesc('moved_at');

        if ($start && $end) {
            $query->whereBetween('moved_at', [$start, $end]);
        }

        $movements = $query->get();

        $totals = [
            'total' => $movements->count(),
            'exit' => $movements->where('type', 'exit')->count(),
            'entry' => $movements->where('type', 'entry')->count(),
        ];

        $rangeLabel = $start && $end
            ? $start->format('Y-m-d') . ' - ' . $end->format('Y-m-d')
            : 'كل الفترات';

        return view('reports.show', [
            'reportTitle' => $title,
            'movements' => $movements,
            'totals' => $totals,
            'rangeLabel' => $rangeLabel,
            'pdfUrl' => $pdfUrl,
            'excelUrl' => $excelUrl,
        ]);
    }

    private function buildReportData(array $validated): array
    {
        $scope = $validated['scope'];
        $title = 'التقرير';
        $query = Movement::with(['vessel', 'exit', 'user'])->orderByDesc('moved_at');
        $rangeLabel = 'كل الفترات';

        if ($scope === 'daily') {
            $title = 'التقرير اليومي';
            $start = Carbon::today();
            $end = Carbon::today()->endOfDay();
            $query->whereBetween('moved_at', [$start, $end]);
            $rangeLabel = $start->format('Y-m-d') . ' - ' . $end->format('Y-m-d');
        } elseif ($scope === 'weekly') {
            $title = 'التقرير الأسبوعي';
            $start = Carbon::now()->startOfWeek();
            $end = Carbon::now()->endOfWeek();
            $query->whereBetween('moved_at', [$start, $end]);
            $rangeLabel = $start->format('Y-m-d') . ' - ' . $end->format('Y-m-d');
        } elseif ($scope === 'monthly') {
            $title = 'التقرير الشهري';
            $start = Carbon::now()->startOfMonth();
            $end = Carbon::now()->endOfMonth();
            $query->whereBetween('moved_at', [$start, $end]);
            $rangeLabel = $start->format('Y-m-d') . ' - ' . $end->format('Y-m-d');
        } elseif ($scope === 'custom') {
            $title = 'تقرير مخصص';
            $start = Carbon::parse($validated['date_from'])->startOfDay();
            $end = Carbon::parse($validated['date_to'])->endOfDay();
            $query->whereBetween('moved_at', [$start, $end]);
            $rangeLabel = $start->format('Y-m-d') . ' - ' . $end->format('Y-m-d');
        } elseif ($scope === 'vessel') {
            $vessel = Vessel::findOrFail($validated['id']);
            $title = 'تقرير وسيلة: ' . $vessel->name;
            $query->where('vessel_id', $vessel->id);
        } elseif ($scope === 'exit') {
            $exit = ExitGate::findOrFail($validated['id']);
            $title = 'تقرير مخرج: ' . $exit->name;
            $query->where('exit_id', $exit->id);
        }

        $movements = $query->get();

        $data = [
            'reportTitle' => $title,
            'movements' => $movements,
            'totals' => [
                'total' => $movements->count(),
                'exit' => $movements->where('type', 'exit')->count(),
                'entry' => $movements->where('type', 'entry')->count(),
            ],
            'rangeLabel' => $rangeLabel,
        ];

        return [$title, $data];
    }

    public function analytics()
    {
        $now = Carbon::now();
        $thirtyDaysAgo = $now->copy()->subDays(30);

        // Total statistics
        $totalMovements = Movement::count();
        $totalExits = Movement::where('type', 'exit')->count();
        $totalEntries = Movement::where('type', 'entry')->count();
        $activeVessels = Vessel::where('status', 'inside')->count();
        $outsideVessels = Vessel::where('status', 'outside')->count();

        // Top 5 vessels by movement count (last 30 days)
        $topVessels = Movement::with('vessel')
            ->where('moved_at', '>=', $thirtyDaysAgo)
            ->groupBy('vessel_id')
            ->selectRaw('vessel_id, COUNT(*) as count')
            ->orderByDesc('count')
            ->limit(5)
            ->get()
            ->map(function ($item) {
                return ['vessel' => $item->vessel, 'count' => $item->count];
            });

        // Top 5 exits by movement count (last 30 days)
        $topExits = Movement::with('exit')
            ->where('moved_at', '>=', $thirtyDaysAgo)
            ->groupBy('exit_id')
            ->selectRaw('exit_id, COUNT(*) as count')
            ->orderByDesc('count')
            ->limit(5)
            ->get()
            ->map(function ($item) {
                return ['exit' => $item->exit, 'count' => $item->count];
            });

        // Hourly movements (last 7 days)
        $hourlyData = Movement::selectRaw('HOUR(moved_at) as hour, COUNT(*) as count')
            ->where('moved_at', '>=', $now->copy()->subDays(7))
            ->groupBy('hour')
            ->orderBy('hour')
            ->get();

        $hourlyLabels = [];
        $hourlyCounts = array_fill(0, 24, 0);
        foreach ($hourlyData as $record) {
            $hourlyCounts[$record->hour] = $record->count;
            if (!isset($hourlyLabels[$record->hour])) {
                $hourlyLabels[$record->hour] = str_pad($record->hour, 2, '0', STR_PAD_LEFT) . ':00';
            }
        }
        for ($i = 0; $i < 24; $i++) {
            if (!isset($hourlyLabels[$i])) {
                $hourlyLabels[$i] = str_pad($i, 2, '0', STR_PAD_LEFT) . ':00';
            }
        }
        ksort($hourlyLabels);
        ksort($hourlyCounts);

        // Daily movements (last 30 days)
        $dailyData = Movement::selectRaw('DATE(moved_at) as date, COUNT(*) as count')
            ->where('moved_at', '>=', $thirtyDaysAgo)
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        $dailyLabels = [];
        $dailyCounts = [];
        foreach ($dailyData as $record) {
            $dailyLabels[] = Carbon::parse($record->date)->format('d/m');
            $dailyCounts[] = $record->count;
        }

        // Entry/Exit ratio by type (last 30 days)
        $typeStats = Movement::selectRaw('type, COUNT(*) as count')
            ->where('moved_at', '>=', $thirtyDaysAgo)
            ->groupBy('type')
            ->get()
            ->pluck('count', 'type');

        // Movement by user (last 30 days)
        $userStats = Movement::with('user')
            ->where('moved_at', '>=', $thirtyDaysAgo)
            ->groupBy('user_id')
            ->selectRaw('user_id, COUNT(*) as count')
            ->orderByDesc('count')
            ->limit(5)
            ->get()
            ->map(function ($item) {
                return ['user' => $item->user, 'count' => $item->count];
            });

        // Average movements per vessel
        $avgMovementsPerVessel = Movement::where('moved_at', '>=', $thirtyDaysAgo)->count() / 
                                 Vessel::count() ?: 0;

        return view('reports.analytics', [
            'totalMovements' => $totalMovements,
            'totalExits' => $totalExits,
            'totalEntries' => $totalEntries,
            'activeVessels' => $activeVessels,
            'outsideVessels' => $outsideVessels,
            'topVessels' => $topVessels,
            'topExits' => $topExits,
            'hourlyLabels' => json_encode(array_values($hourlyLabels)),
            'hourlyCounts' => json_encode(array_values($hourlyCounts)),
            'dailyLabels' => json_encode($dailyLabels),
            'dailyCounts' => json_encode($dailyCounts),
            'typeStats' => $typeStats,
            'userStats' => $userStats,
            'avgMovementsPerVessel' => round($avgMovementsPerVessel, 2),
        ]);
    }
}
