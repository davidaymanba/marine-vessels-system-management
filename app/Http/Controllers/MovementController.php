<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\RecordsAuditLog;
use App\Models\ExitGate;
use App\Models\Movement;
use App\Models\User;
use App\Models\Vessel;
use App\Notifications\MovementStatusNotification;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;
use Illuminate\Validation\ValidationException;

class MovementController extends Controller
{
    use RecordsAuditLog;

    public function index(Request $request)
    {
        $query = Movement::with(['vessel', 'exit', 'user'])
            ->orderByDesc('moved_at');

        if ($request->filled('type')) {
            $query->where('type', $request->string('type'));
        }

        if ($request->filled('exit_id')) {
            $query->where('exit_id', $request->integer('exit_id'));
        }

        if ($request->filled('vessel_id')) {
            $query->where('vessel_id', $request->integer('vessel_id'));
        }

        if ($request->filled('date_from')) {
            $query->whereDate('moved_at', '>=', Carbon::parse($request->string('date_from')));
        }

        if ($request->filled('date_to')) {
            $query->whereDate('moved_at', '<=', Carbon::parse($request->string('date_to')));
        }

        $movements = $query->paginate(20)->withQueryString();
        $exits = ExitGate::orderBy('name')->get();
        $vessels = Vessel::orderBy('name')->get();

        return view('movements.index', compact('movements', 'exits', 'vessels'));
    }

    public function checkoutForm()
    {
        $vessels = Vessel::active()
            ->inside()
            ->where('maintenance_status', 'operational')
            ->orderBy('name')
            ->get();
        $exits = ExitGate::where('is_active', true)->orderBy('name')->get();

        return view('movements.checkout', compact('vessels', 'exits'));
    }

    public function checkout(Request $request)
    {
        $validated = $request->validate([
            'vessel_id' => ['required', 'exists:vessels,id'],
            'exit_id' => ['required', 'exists:exits,id'],
            'notes' => ['nullable', 'string'],
        ]);

        DB::transaction(function () use ($validated) {
            $vessel = Vessel::lockForUpdate()->findOrFail($validated['vessel_id']);

            if ($vessel->archived_at) {
                throw ValidationException::withMessages([
                    'vessel_id' => 'لا يمكن تسجيل حركة على وسيلة مؤرشفة.',
                ]);
            }

            if ($vessel->maintenance_status !== 'operational') {
                throw ValidationException::withMessages([
                    'vessel_id' => 'لا يمكن تسجيل حركة على وسيلة غير تشغيلية.',
                ]);
            }

            if ($vessel->status === 'outside') {
                throw ValidationException::withMessages([
                    'vessel_id' => 'لا يمكن تسجيل خروج وسيلة خارجية بالفعل.',
                ]);
            }

            $vessel->update(['status' => 'outside']);

            $movement = Movement::create([
                'vessel_id' => $vessel->id,
                'exit_id' => $validated['exit_id'],
                'user_id' => auth()->id(),
                'type' => 'exit',
                'notes' => $validated['notes'] ?? null,
                'moved_at' => now(),
            ]);

            $this->audit('checked-out', $movement, 'تم تسجيل خروج وسيلة.', [
                'vessel_id' => $vessel->id,
                'exit_id' => $validated['exit_id'],
                'notes' => $validated['notes'] ?? null,
            ]);

            $this->notifyMovement($movement, 'تم تسجيل خروج وسيلة بحرية.');
        });

        return redirect()
            ->route('movements.checkout')
            ->with('success', 'تم تسجيل خروج الوسيلة بنجاح.');
    }

    public function scanPage()
    {
        return view('movements.scan');
    }

    public function checkinByScan(Request $request)
    {
        $validated = $request->validate([
            'barcode' => ['required', 'string', 'max:255'],
        ]);

        $barcode = trim($validated['barcode']);

        $vessel = Vessel::active()
            ->where(function ($query) use ($barcode) {
                $query->where('barcode', $barcode)
                    ->orWhere('vessel_number', $barcode);
            })
            ->first();

        if (!$vessel) {
            return redirect()
                ->back()
                ->withErrors(['barcode' => 'لم يتم العثور على وسيلة بهذا الباركود.'])
                ->withInput();
        }

        DB::transaction(function () use ($vessel) {
            $vessel->refresh();

            if ($vessel->maintenance_status !== 'operational') {
                throw ValidationException::withMessages([
                    'barcode' => 'لا يمكن تسجيل الحركة على وسيلة غير تشغيلية.',
                ]);
            }

            if ($vessel->status === 'inside') {
                throw ValidationException::withMessages([
                    'barcode' => 'لا يمكن تسجيل دخول وسيلة داخلية بالفعل.',
                ]);
            }

            $vessel->update(['status' => 'inside']);

            $movement = Movement::create([
                'vessel_id' => $vessel->id,
                'exit_id' => null,
                'user_id' => auth()->id(),
                'type' => 'entry',
                'notes' => null,
                'moved_at' => now(),
            ]);

            $this->audit('checked-in', $movement, 'تم تسجيل دخول وسيلة.', [
                'vessel_id' => $vessel->id,
            ]);

            $this->notifyMovement($movement, 'تم تسجيل دخول وسيلة بحرية.');
        });

        return redirect()
            ->back()
            ->with('success', 'تم تسجيل دخول الوسيلة بنجاح.');
    }

    private function notifyMovement(Movement $movement, string $label): void
    {
        $recipients = User::query()
            ->where('id', '!=', auth()->id())
            ->get();

        if ($recipients->isNotEmpty()) {
            Notification::send($recipients, new MovementStatusNotification($movement, $label));
        }
    }
}
