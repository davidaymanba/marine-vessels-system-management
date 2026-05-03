<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\RecordsAuditLog;
use App\Models\Vessel;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class VesselController extends Controller
{
    use RecordsAuditLog;

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $status = request('status');
        $maintenanceStatus = request('maintenance_status');
        $archived = request()->boolean('archived');

        $query = Vessel::query()->orderBy('name');

        if (!$archived) {
            $query->active();
        } else {
            $query->archived();
        }

        if (in_array($status, ['inside', 'outside'], true)) {
            $query->where('status', $status);
        }

        if (in_array($maintenanceStatus, ['operational', 'maintenance', 'out_of_service'], true)) {
            $query->where('maintenance_status', $maintenanceStatus);
        }

        $vessels = $query->paginate(15)->withQueryString();

        return view('vessels.index', compact('vessels', 'status', 'maintenanceStatus', 'archived'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('vessels.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'vessel_number' => ['required', 'string', 'max:50', 'unique:vessels,vessel_number'],
            'vessel_type' => ['nullable', 'string', 'max:100'],
            'owner_name' => ['nullable', 'string', 'max:255'],
            'capacity' => ['nullable', 'integer', 'min:1'],
            'maintenance_status' => ['required', 'in:operational,maintenance,out_of_service'],
            'description' => ['nullable', 'string'],
            'image' => ['nullable', 'image', 'max:2048'],
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('vessels', 'public');
        }

        $vessel = Vessel::create([
            'name' => $validated['name'],
            'vessel_number' => $validated['vessel_number'],
            'vessel_type' => $validated['vessel_type'] ?? null,
            'owner_name' => $validated['owner_name'] ?? null,
            'capacity' => $validated['capacity'] ?? null,
            'maintenance_status' => $validated['maintenance_status'],
            'barcode' => (string) Str::uuid(),
            'status' => 'inside',
            'description' => $validated['description'] ?? null,
            'image' => $imagePath,
        ]);

        $this->audit('created', $vessel, 'تم إنشاء وسيلة بحرية جديدة.', [
            'name' => $vessel->name,
            'vessel_number' => $vessel->vessel_number,
        ]);

        return redirect()
            ->route('vessels.index')
            ->with('success', 'تمت إضافة الوسيلة بنجاح.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $vessel = Vessel::with(['movements.exit', 'movements.user'])
            ->findOrFail($id);

        return view('vessels.show', compact('vessel'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $vessel = Vessel::findOrFail($id);

        return view('vessels.edit', compact('vessel'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $vessel = Vessel::findOrFail($id);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'vessel_number' => ['required', 'string', 'max:50', 'unique:vessels,vessel_number,' . $vessel->id],
            'vessel_type' => ['nullable', 'string', 'max:100'],
            'owner_name' => ['nullable', 'string', 'max:255'],
            'capacity' => ['nullable', 'integer', 'min:1'],
            'maintenance_status' => ['required', 'in:operational,maintenance,out_of_service'],
            'description' => ['nullable', 'string'],
            'image' => ['nullable', 'image', 'max:2048'],
        ]);

        if ($request->hasFile('image')) {
            $vessel->image = $request->file('image')->store('vessels', 'public');
        }

        $original = $vessel->getOriginal();

        $vessel->fill([
            'name' => $validated['name'],
            'vessel_number' => $validated['vessel_number'],
            'vessel_type' => $validated['vessel_type'] ?? null,
            'owner_name' => $validated['owner_name'] ?? null,
            'capacity' => $validated['capacity'] ?? null,
            'maintenance_status' => $validated['maintenance_status'],
            'description' => $validated['description'] ?? null,
        ])->save();

        $this->audit('updated', $vessel, 'تم تحديث بيانات وسيلة بحرية.', [
            'before' => $original,
            'after' => $vessel->fresh()->toArray(),
        ]);

        return redirect()
            ->route('vessels.show', $vessel)
            ->with('success', 'تم تحديث بيانات الوسيلة بنجاح.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $vessel = Vessel::findOrFail($id);
        $payload = $vessel->toArray();
        $vessel->delete();

        $this->audit('deleted', $vessel, 'تم حذف وسيلة بحرية.', [
            'snapshot' => $payload,
        ]);

        return redirect()
            ->route('vessels.index')
            ->with('success', 'تم حذف الوسيلة بنجاح.');
    }

    public function archive(string $id)
    {
        $vessel = Vessel::findOrFail($id);
        $vessel->update(['archived_at' => now()]);

        $this->audit('archived', $vessel, 'تم أرشفة وسيلة بحرية.', [
            'name' => $vessel->name,
            'vessel_number' => $vessel->vessel_number,
        ]);

        return redirect()
            ->route('vessels.show', $vessel)
            ->with('success', 'تمت أرشفة الوسيلة بنجاح.');
    }

    public function restore(string $id)
    {
        $vessel = Vessel::findOrFail($id);
        $vessel->update(['archived_at' => null]);

        $this->audit('restored', $vessel, 'تمت استعادة وسيلة بحرية من الأرشيف.', [
            'name' => $vessel->name,
            'vessel_number' => $vessel->vessel_number,
        ]);

        return redirect()
            ->route('vessels.show', $vessel)
            ->with('success', 'تمت استعادة الوسيلة بنجاح.');
    }

    public function printBarcode(string $id)
    {
        $vessel = Vessel::findOrFail($id);

        return view('vessels.barcode', compact('vessel'));
    }
}
