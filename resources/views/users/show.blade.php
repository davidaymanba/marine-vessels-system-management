<x-app-layout>
    <div class="max-w-3xl space-y-6">
        <h1 class="text-2xl font-bold text-slate-900">{{ $user->name }}</h1>
        <div class="rounded-2xl bg-white p-6 shadow-sm border border-slate-200 space-y-3">
            <div><span class="text-slate-500">البريد:</span> {{ $user->email }}</div>
            <div><span class="text-slate-500">WhatsApp:</span> {{ $user->phone ?? '-' }}</div>
            <div><span class="text-slate-500">الدور:</span> {{ $user->role }}</div>
        </div>
    </div>
</x-app-layout>
