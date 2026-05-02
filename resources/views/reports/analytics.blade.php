@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white">📊 التحليلات المتقدمة</h1>
            <p class="text-gray-600 dark:text-gray-400 mt-2">تقارير ومؤشرات الأداء (KPI)</p>
        </div>
    </div>

    <!-- Key Metrics Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">
        <!-- Total Movements -->
        <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg shadow-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm opacity-90">إجمالي الحركات</p>
                    <p class="text-3xl font-bold mt-2">{{ $totalMovements }}</p>
                </div>
                <div class="text-4xl opacity-30">📦</div>
            </div>
        </div>

        <!-- Total Exits -->
        <div class="bg-gradient-to-br from-red-500 to-red-600 rounded-lg shadow-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm opacity-90">إجمالي الخروجات</p>
                    <p class="text-3xl font-bold mt-2">{{ $totalExits }}</p>
                </div>
                <div class="text-4xl opacity-30">🚀</div>
            </div>
        </div>

        <!-- Total Entries -->
        <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-lg shadow-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm opacity-90">إجمالي الدخول</p>
                    <p class="text-3xl font-bold mt-2">{{ $totalEntries }}</p>
                </div>
                <div class="text-4xl opacity-30">📥</div>
            </div>
        </div>

        <!-- Active Vessels -->
        <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-lg shadow-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm opacity-90">وسائل نشطة</p>
                    <p class="text-3xl font-bold mt-2">{{ $activeVessels }}</p>
                </div>
                <div class="text-4xl opacity-30">⚓</div>
            </div>
        </div>

        <!-- Outside Vessels -->
        <div class="bg-gradient-to-br from-orange-500 to-orange-600 rounded-lg shadow-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm opacity-90">وسائل خارجة</p>
                    <p class="text-3xl font-bold mt-2">{{ $outsideVessels }}</p>
                </div>
                <div class="text-4xl opacity-30">🌊</div>
            </div>
        </div>
    </div>

    <!-- Charts Section -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Hourly Trend Chart -->
        <div class="bg-white dark:bg-slate-800 rounded-lg shadow p-6">
            <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-4">🕐 الحركات بالساعات (آخر 7 أيام)</h2>
            <canvas id="hourlyChart"></canvas>
        </div>

        <!-- Daily Trend Chart -->
        <div class="bg-white dark:bg-slate-800 rounded-lg shadow p-6">
            <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-4">📈 الحركات اليومية (آخر 30 يوم)</h2>
            <canvas id="dailyChart"></canvas>
        </div>
    </div>

    <!-- Type Distribution and User Stats -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Entry/Exit Ratio -->
        <div class="bg-white dark:bg-slate-800 rounded-lg shadow p-6">
            <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-4">📊 نسبة الدخول والخروج</h2>
            <canvas id="typeChart"></canvas>
        </div>

        <!-- Top Users -->
        <div class="bg-white dark:bg-slate-800 rounded-lg shadow p-6">
            <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-4">👥 أكثر المشغلين نشاطاً</h2>
            <div class="space-y-3">
                @forelse($userStats as $stat)
                    <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-slate-700 rounded">
                        <div class="flex items-center gap-2">
                            <div class="w-10 h-10 rounded-full bg-gradient-to-br from-blue-400 to-blue-600 flex items-center justify-center text-white text-sm font-bold">
                                {{ strtoupper(substr($stat['user']->name, 0, 1)) }}
                            </div>
                            <span class="text-gray-900 dark:text-white font-medium">{{ $stat['user']->name }}</span>
                        </div>
                        <span class="bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-200 px-3 py-1 rounded-full text-sm font-semibold">
                            {{ $stat['count'] }}
                        </span>
                    </div>
                @empty
                    <p class="text-gray-500 dark:text-gray-400 py-4">لا توجد بيانات</p>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Top Vessels and Exits -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Top Vessels -->
        <div class="bg-white dark:bg-slate-800 rounded-lg shadow p-6">
            <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-4">⚓ أكثر الوسائل استخداماً</h2>
            <div class="space-y-3">
                @forelse($topVessels as $vessel)
                    <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-slate-700 rounded">
                        <span class="text-gray-900 dark:text-white font-medium">{{ $vessel['vessel']->name }}</span>
                        <div class="flex items-center gap-2">
                            <div class="w-8 h-8 rounded-full bg-gradient-to-br from-purple-400 to-purple-600 flex items-center justify-center text-white text-sm font-bold">
                                {{ $vessel['count'] }}
                            </div>
                            <span class="text-gray-500 dark:text-gray-400 text-sm">حركة</span>
                        </div>
                    </div>
                @empty
                    <p class="text-gray-500 dark:text-gray-400 py-4">لا توجد بيانات</p>
                @endforelse
            </div>
        </div>

        <!-- Top Exits -->
        <div class="bg-white dark:bg-slate-800 rounded-lg shadow p-6">
            <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-4">🚪 أكثر المخارج استخداماً</h2>
            <div class="space-y-3">
                @forelse($topExits as $exit)
                    <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-slate-700 rounded">
                        <span class="text-gray-900 dark:text-white font-medium">{{ $exit['exit']->name }}</span>
                        <div class="flex items-center gap-2">
                            <div class="w-8 h-8 rounded-full bg-gradient-to-br from-orange-400 to-orange-600 flex items-center justify-center text-white text-sm font-bold">
                                {{ $exit['count'] }}
                            </div>
                            <span class="text-gray-500 dark:text-gray-400 text-sm">حركة</span>
                        </div>
                    </div>
                @empty
                    <p class="text-gray-500 dark:text-gray-400 py-4">لا توجد بيانات</p>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Average Stats -->
    <div class="bg-white dark:bg-slate-800 rounded-lg shadow p-6">
        <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-4">📐 المتوسطات والإحصائيات</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="p-4 bg-gray-50 dark:bg-slate-700 rounded">
                <p class="text-gray-600 dark:text-gray-400 text-sm">متوسط الحركات لكل وسيلة</p>
                <p class="text-2xl font-bold text-blue-600 dark:text-blue-400 mt-2">{{ $avgMovementsPerVessel }}</p>
            </div>
            <div class="p-4 bg-gray-50 dark:bg-slate-700 rounded">
                <p class="text-gray-600 dark:text-gray-400 text-sm">نسبة الخروج</p>
                <p class="text-2xl font-bold text-red-600 dark:text-red-400 mt-2">
                    {{ $totalMovements > 0 ? round(($totalExits / $totalMovements) * 100, 1) : 0 }}%
                </p>
            </div>
            <div class="p-4 bg-gray-50 dark:bg-slate-700 rounded">
                <p class="text-gray-600 dark:text-gray-400 text-sm">نسبة الدخول</p>
                <p class="text-2xl font-bold text-green-600 dark:text-green-400 mt-2">
                    {{ $totalMovements > 0 ? round(($totalEntries / $totalMovements) * 100, 1) : 0 }}%
                </p>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Hourly Chart
    const hourlyCtx = document.getElementById('hourlyChart').getContext('2d');
    new Chart(hourlyCtx, {
        type: 'line',
        data: {
            labels: {!! $hourlyLabels !!},
            datasets: [{
                label: 'عدد الحركات',
                data: {!! $hourlyCounts !!},
                borderColor: '#0ea5e9',
                backgroundColor: 'rgba(14, 165, 233, 0.1)',
                fill: true,
                tension: 0.4,
                borderWidth: 2,
                pointRadius: 4,
                pointBackgroundColor: '#0ea5e9',
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    labels: {
                        color: document.documentElement.classList.contains('dark') ? '#fff' : '#333'
                    }
                }
            },
            scales: {
                y: {
                    ticks: {
                        color: document.documentElement.classList.contains('dark') ? '#fff' : '#333'
                    },
                    grid: {
                        color: document.documentElement.classList.contains('dark') ? '#475569' : '#e2e8f0'
                    }
                },
                x: {
                    ticks: {
                        color: document.documentElement.classList.contains('dark') ? '#fff' : '#333'
                    },
                    grid: {
                        color: document.documentElement.classList.contains('dark') ? '#475569' : '#e2e8f0'
                    }
                }
            }
        }
    });

    // Daily Chart
    const dailyCtx = document.getElementById('dailyChart').getContext('2d');
    new Chart(dailyCtx, {
        type: 'bar',
        data: {
            labels: {!! $dailyLabels !!},
            datasets: [{
                label: 'عدد الحركات اليومية',
                data: {!! $dailyCounts !!},
                backgroundColor: '#8b5cf6',
                borderColor: '#7c3aed',
                borderWidth: 1,
                borderRadius: 4,
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    labels: {
                        color: document.documentElement.classList.contains('dark') ? '#fff' : '#333'
                    }
                }
            },
            scales: {
                y: {
                    ticks: {
                        color: document.documentElement.classList.contains('dark') ? '#fff' : '#333'
                    },
                    grid: {
                        color: document.documentElement.classList.contains('dark') ? '#475569' : '#e2e8f0'
                    }
                },
                x: {
                    ticks: {
                        color: document.documentElement.classList.contains('dark') ? '#fff' : '#333'
                    },
                    grid: {
                        color: document.documentElement.classList.contains('dark') ? '#475569' : '#e2e8f0'
                    }
                }
            }
        }
    });

    // Type Chart (Doughnut)
    const typeCtx = document.getElementById('typeChart').getContext('2d');
    new Chart(typeCtx, {
        type: 'doughnut',
        data: {
            labels: ['خروج', 'دخول'],
            datasets: [{
                data: [{{ $typeStats->get('exit', 0) }}, {{ $typeStats->get('entry', 0) }}],
                backgroundColor: ['#ef4444', '#22c55e'],
                borderColor: ['#dc2626', '#16a34a'],
                borderWidth: 2,
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    labels: {
                        color: document.documentElement.classList.contains('dark') ? '#fff' : '#333'
                    }
                }
            }
        }
    });
</script>
@endsection
