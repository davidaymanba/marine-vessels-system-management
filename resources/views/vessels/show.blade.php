<x-app-layout>
    <div class="space-y-6">
        <div class="flex flex-col gap-4 lg:flex-row lg:items-start lg:justify-between">
            <div>
                <h1 class="text-2xl font-bold text-slate-900">{{ $vessel->name }}</h1>
                <p class="text-slate-500 mt-1">{{ $vessel->vessel_number }}</p>
            </div>
            <a href="{{ route('vessels.barcode', $vessel) }}" class="rounded-xl bg-slate-900 px-5 py-3 text-white">طباعة الباركود</a>
        </div>

        <div class="grid gap-6 xl:grid-cols-3">
            <div class="rounded-2xl bg-white p-6 shadow-sm border border-slate-200 xl:col-span-1 space-y-3">
                <div class="text-sm text-slate-500">الحالة</div>
                <div class="inline-flex rounded-full px-3 py-1 text-xs font-semibold {{ $vessel->status === 'inside' ? 'bg-emerald-100 text-emerald-700' : 'bg-rose-100 text-rose-700' }}">
                    {{ $vessel->status === 'inside' ? 'داخل' : 'خارج' }}
                </div>
                <div class="text-sm text-slate-500 pt-2">الباركود</div>
                <div class="font-mono text-sm break-all">{{ $vessel->barcode }}</div>
                @if($vessel->description)
                    <div class="pt-2 text-sm text-slate-600">{{ $vessel->description }}</div>
                @endif
            </div>

            <div class="rounded-2xl bg-white shadow-sm border border-slate-200 overflow-hidden xl:col-span-2">
                <div class="px-6 py-4 border-b border-slate-200 font-bold">سجل الحركات</div>
                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm">
                        <thead class="bg-slate-50 text-slate-500">
                            <tr>
                                <th class="px-6 py-3 text-right">النوع</th>
                                <th class="px-6 py-3 text-right">المخرج</th>
                                <th class="px-6 py-3 text-right">المستخدم</th>
                                <th class="px-6 py-3 text-right">التاريخ</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($vessel->movements as $movement)
                                <tr>
                                    <td class="px-6 py-4">{{ $movement->type === 'exit' ? 'خروج' : 'دخول' }}</td>
                                    <td class="px-6 py-4">{{ $movement->exit?->name ?? '-' }}</td>
                                    <td class="px-6 py-4">{{ $movement->user?->name }}</td>
                                    <td class="px-6 py-4">{{ optional($movement->moved_at)->format('Y-m-d H:i:s') }}</td>
                                </tr>
                            @empty
                                <tr><td colspan="4" class="px-6 py-8 text-center text-slate-500">لا توجد حركات.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
