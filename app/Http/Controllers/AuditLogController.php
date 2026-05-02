<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use App\Models\ExitGate;
use App\Models\Movement;
use App\Models\User;
use App\Models\Vessel;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AuditLogController extends Controller
{
    public function index(Request $request)
    {
        $query = AuditLog::with('user')->latest();

        if ($request->filled('action')) {
            $query->where('action', $request->string('action'));
        }

        if ($request->filled('subject_type')) {
            $query->where('subject_type', $request->string('subject_type'));
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', Carbon::parse($request->string('date_from')));
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', Carbon::parse($request->string('date_to')));
        }

        $logs = $query->paginate(25)->withQueryString();
        $actions = ['created', 'updated', 'deleted', 'checked-out', 'checked-in'];
        $subjectTypes = [Vessel::class, ExitGate::class, Movement::class, User::class];

        return view('audit-logs.index', compact('logs', 'actions', 'subjectTypes'));
    }
}
