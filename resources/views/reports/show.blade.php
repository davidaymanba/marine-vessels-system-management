<x-app-layout>
    <div class="space-y-6">
        <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
            <h1 class="text-2xl font-bold text-slate-900">{{ $reportTitle }}</h1>
            <div class="flex items-center gap-3">
                <p class="text-slate-500">{{ $rangeLabel }}</p>
                @if(!empty($pdfUrl))
                    <a href="{{ $pdfUrl }}" class="rounded-xl bg-slate-900 px-4 py-2 text-sm text-white">تحميل PDF</a>
                @endif
                @if(!empty($excelUrl))
                    <a href="{{ $excelUrl }}" class="rounded-xl bg-emerald-600 px-4 py-2 text-sm text-white">تحميل Excel</a>
                @endif
            </div>
        </div>

        <div class="grid gap-4 md:grid-cols-3">
            <div class="rounded-2xl bg-white p-6 shadow-sm border border-slate-200">
                <div class="text-sm text-slate-500">الإجمالي</div>
                <div class="mt-2 text-3xl font-bold">{{ $totals['total'] }}</div>
            </div>
            <div class="rounded-2xl bg-white p-6 shadow-sm border border-slate-200">
                <div class="text-sm text-slate-500">حركات الخروج</div>
                <div class="mt-2 text-3xl font-bold text-amber-600">{{ $totals['exit'] }}</div>
            </div>
            <div class="rounded-2xl bg-white p-6 shadow-sm border border-slate-200">
                <div class="text-sm text-slate-500">حركات الدخول</div>
                <div class="mt-2 text-3xl font-bold text-emerald-600">{{ $totals['entry'] }}</div>
            </div>
        </div>

        <div class="rounded-2xl bg-white shadow-sm border border-slate-200 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead class="bg-slate-50 text-slate-500">
                        <tr>
                            <th class="px-6 py-3 text-right">الوسيلة</th>
                            <th class="px-6 py-3 text-right">النوع</th>
                            <th class="px-6 py-3 text-right">المخرج</th>
                            <th class="px-6 py-3 text-right">المستخدم</th>
                            <th class="px-6 py-3 text-right">الوقت</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($movements as $movement)
                            <tr>
                                <td class="px-6 py-4">{{ $movement->vessel?->name }}</td>
                                <td class="px-6 py-4">{{ $movement->type === 'exit' ? 'خروج' : 'دخول' }}</td>
                                <td class="px-6 py-4">{{ $movement->exit?->name ?? '-' }}</td>
                                <td class="px-6 py-4">{{ $movement->user?->name }}</td>
                                <td class="px-6 py-4">{{ optional($movement->moved_at)->format('Y-m-d H:i:s') }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="px-6 py-8 text-center text-slate-500">لا توجد نتائج.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
