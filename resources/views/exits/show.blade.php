<x-app-layout>
    <div class="max-w-3xl space-y-6">
        <h1 class="text-2xl font-bold text-slate-900">{{ $exit->name }}</h1>
        <div class="rounded-2xl bg-white p-6 shadow-sm border border-slate-200 space-y-3">
            <div><span class="text-slate-500">الحالة:</span> {{ $exit->is_active ? 'مفعل' : 'معطل' }}</div>
            <div><span class="text-slate-500">الوصف:</span> {{ $exit->description ?? '-' }}</div>
        </div>
    </div>
</x-app-layout>
