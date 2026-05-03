<x-app-layout>
    <div class="space-y-6" x-data="dashboardChart()" x-init="init()">
        <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-4">
            <div class="rounded-2xl bg-white p-6 shadow-sm border border-slate-200">
                <div class="text-sm text-slate-500">إجمالي الوسائل</div>
                <div class="mt-2 text-3xl font-bold text-slate-900">{{ $totalVessels }}</div>
            </div>
            <div class="rounded-2xl bg-white p-6 shadow-sm border border-slate-200">
                <div class="text-sm text-slate-500">حالياً خارج</div>
                <div class="mt-2 text-3xl font-bold text-rose-600">{{ $outsideCount }}</div>
            </div>
            <div class="rounded-2xl bg-white p-6 shadow-sm border border-slate-200">
                <div class="text-sm text-slate-500">داخل</div>
                <div class="mt-2 text-3xl font-bold text-emerald-600">{{ $insideCount }}</div>
            </div>
            <div class="rounded-2xl bg-gradient-to-br from-sky-500 to-cyan-500 p-6 text-white shadow-sm">
                <div class="text-sm text-white/80">حركات اليوم</div>
                <div class="mt-2 text-3xl font-bold">{{ $latestMovements->count() }}</div>
                <div class="mt-1 text-sm text-white/80">آخر 10 حركات</div>
            </div>
            <div class="rounded-2xl bg-white p-6 shadow-sm border border-slate-200">
                <div class="text-sm text-slate-500">مؤرشفة</div>
                <div class="mt-2 text-3xl font-bold text-slate-900">{{ $archivedCount }}</div>
            </div>
            <div class="rounded-2xl bg-white p-6 shadow-sm border border-slate-200">
                <div class="text-sm text-slate-500">تشغيلية</div>
                <div class="mt-2 text-3xl font-bold text-emerald-600">{{ $operationalCount }}</div>
            </div>
            <div class="rounded-2xl bg-white p-6 shadow-sm border border-slate-200">
                <div class="text-sm text-slate-500">في الصيانة</div>
                <div class="mt-2 text-3xl font-bold text-amber-600">{{ $maintenanceCount }}</div>
            </div>
            <div class="rounded-2xl bg-white p-6 shadow-sm border border-slate-200">
                <div class="text-sm text-slate-500">خارج الخدمة</div>
                <div class="mt-2 text-3xl font-bold text-rose-600">{{ $outOfServiceCount }}</div>
            </div>
        </div>

        <div class="grid gap-6 xl:grid-cols-3">
            <div class="xl:col-span-2 rounded-2xl bg-white shadow-sm border border-slate-200 overflow-hidden">
                <div class="flex items-center justify-between px-6 py-4 border-b border-slate-200">
                    <h3 class="font-bold text-slate-900">آخر 10 حركات اليوم</h3>
                    <a href="{{ route('movements.index') }}" class="text-sm text-sky-600 hover:text-sky-700">عرض الكل</a>
                </div>
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
                            @forelse($latestMovements as $movement)
                                <tr>
                                    <td class="px-6 py-4 font-medium">{{ $movement->vessel?->name }}</td>
                                    <td class="px-6 py-4">
                                        <span class="inline-flex rounded-full px-3 py-1 text-xs font-semibold {{ $movement->type === 'exit' ? 'bg-amber-100 text-amber-700' : 'bg-emerald-100 text-emerald-700' }}">
                                            {{ $movement->type === 'exit' ? 'خروج' : 'دخول' }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4">{{ $movement->exit?->name ?? '-' }}</td>
                                    <td class="px-6 py-4">{{ $movement->user?->name }}</td>
                                    <td class="px-6 py-4 text-slate-500">{{ optional($movement->moved_at)->format('Y-m-d H:i:s') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-8 text-center text-slate-500">لا توجد حركات اليوم.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="rounded-2xl bg-white shadow-sm border border-slate-200 overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-200">
                    <h3 class="font-bold text-slate-900">إحصائيات المخارج</h3>
                </div>
                <div class="p-6">
                    <div class="relative h-72">
                        <canvas id="exitChart"></canvas>
                    </div>
                    <div class="mt-4 space-y-3">
                        @forelse($exitStats as $exit)
                            <div class="flex items-center justify-between rounded-xl bg-slate-50 px-4 py-3 text-sm">
                                <span>{{ $exit->name }}</span>
                                <span class="font-semibold text-slate-700">{{ $exit->movements_count }}</span>
                            </div>
                        @empty
                            <div class="text-sm text-slate-500">لا توجد مخارج مفعلة.</div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

        <script>
            function dashboardChart() {
                return {
                    init() {
                        const canvas = document.getElementById('exitChart');
                        if (!canvas || !window.Chart) {
                            return;
                        }

                        const labels = @json($chartLabels);
                        const values = @json($chartValues);

                        new Chart(canvas, {
                            type: 'doughnut',
                            data: {
                                labels,
                                datasets: [{
                                    data: values,
                                    backgroundColor: ['#0EA5E9', '#14B8A6', '#F59E0B', '#EF4444', '#6366F1'],
                                    borderWidth: 0,
                                }],
                            },
                            options: {
                                responsive: true,
                                maintainAspectRatio: false,
                                plugins: {
                                    legend: {
                                        position: 'bottom',
                                    },
                                },
                            },
                        });
                    },
                };
            }
        </script>
    </div>
</x-app-layout>
