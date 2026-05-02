<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\RecordsAuditLog;
use App\Models\ExitGate;
use Illuminate\Http\Request;

class ExitController extends Controller
{
    use RecordsAuditLog;

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $exits = ExitGate::orderBy('name')->paginate(15);

        return view('exits.index', compact('exits'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('exits.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $exit = ExitGate::create([
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
            'is_active' => (bool) ($validated['is_active'] ?? false),
        ]);

        $this->audit('created', $exit, 'تم إنشاء مخرج جديد.', [
            'name' => $exit->name,
        ]);

        return redirect()
            ->route('exits.index')
            ->with('success', 'تمت إضافة المخرج بنجاح.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $exit = ExitGate::findOrFail($id);

        return view('exits.show', compact('exit'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $exit = ExitGate::findOrFail($id);

        return view('exits.edit', compact('exit'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $exit = ExitGate::findOrFail($id);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $exit->update([
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
            'is_active' => (bool) ($validated['is_active'] ?? false),
        ]);

        $this->audit('updated', $exit, 'تم تحديث بيانات مخرج.', [
            'after' => $exit->fresh()->toArray(),
        ]);

        return redirect()
            ->route('exits.index')
            ->with('success', 'تم تحديث المخرج بنجاح.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $exit = ExitGate::findOrFail($id);
        $payload = $exit->toArray();
        $exit->delete();

        $this->audit('deleted', $exit, 'تم حذف مخرج.', [
            'snapshot' => $payload,
        ]);

        return redirect()
            ->route('exits.index')
            ->with('success', 'تم حذف المخرج بنجاح.');
    }
}
