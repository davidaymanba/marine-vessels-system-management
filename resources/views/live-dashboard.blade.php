@extends('layouts.app')

@section('content')
<div class="space-y-6" x-data="liveDashboard()" x-init="init()">
    <!-- Header -->
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white">🚀 لوحة التحكم الحية</h1>
            <p class="text-gray-600 dark:text-gray-400 mt-2">تحديثات فورية للحركات والوسائل</p>
        </div>
        <div class="text-right">
            <div class="text-sm text-gray-500 dark:text-gray-400">آخر تحديث:</div>
            <div class="text-lg font-mono text-blue-600 dark:text-blue-400" x-data="liveTime()" x-text="time"></div>
        </div>
    </div>

    <!-- Live Metrics -->
    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-4">
        <!-- Active Vessels -->
        <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg shadow-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm opacity-90">الوسائل النشطة</p>
                    <p class="text-4xl font-bold mt-2" x-text="activeVessels">-</p>
                </div>
                <div class="text-5xl opacity-30">⚓</div>
            </div>
        </div>

        <!-- Outside Vessels -->
        <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-lg shadow-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm opacity-90">الوسائل خارج المحطة</p>
                    <p class="text-4xl font-bold mt-2" x-text="outsideVessels">-</p>
                </div>
                <div class="text-5xl opacity-30">🌊</div>
            </div>
        </div>

        <!-- Occupancy Rate -->
        <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-lg shadow-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm opacity-90">معدل الإشغال</p>
                    <p class="text-4xl font-bold mt-2" x-text="occupancyRate + '%'">-</p>
                </div>
                <div class="text-5xl opacity-30">📊</div>
            </div>
        </div>

        <!-- Last Hour Activity -->
        <div class="bg-gradient-to-br from-orange-500 to-orange-600 rounded-lg shadow-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm opacity-90">الحركات (آخر ساعة)</p>
                    <p class="text-4xl font-bold mt-2">
                        <span x-text="hourStats.total || 0">-</span>
                    </p>
                </div>
                <div class="text-5xl opacity-30">📦</div>
            </div>
        </div>

        <div class="bg-gradient-to-br from-slate-600 to-slate-700 rounded-lg shadow-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm opacity-90">المؤرشفة</p>
                    <p class="text-4xl font-bold mt-2" x-text="archivedVessels || 0">-</p>
                </div>
                <div class="text-5xl opacity-30">🗄️</div>
            </div>
        </div>

        <div class="bg-gradient-to-br from-emerald-500 to-emerald-600 rounded-lg shadow-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm opacity-90">التشغيلية</p>
                    <p class="text-4xl font-bold mt-2" x-text="operationalVessels || 0">-</p>
                </div>
                <div class="text-5xl opacity-30">✅</div>
            </div>
        </div>

        <div class="bg-gradient-to-br from-amber-500 to-amber-600 rounded-lg shadow-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm opacity-90">في الصيانة</p>
                    <p class="text-4xl font-bold mt-2" x-text="maintenanceVessels || 0">-</p>
                </div>
                <div class="text-5xl opacity-30">🛠️</div>
            </div>
        </div>

        <div class="bg-gradient-to-br from-rose-500 to-rose-600 rounded-lg shadow-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm opacity-90">خارج الخدمة</p>
                    <p class="text-4xl font-bold mt-2" x-text="outOfServiceVessels || 0">-</p>
                </div>
                <div class="text-5xl opacity-30">⛔</div>
            </div>
        </div>
    </div>

    <!-- Real-time Alerts -->
    <div class="space-y-3" x-show="notifications.length > 0">
        <template x-for="notif in notifications.slice(0, 3)" :key="notif.id">
            <div class="animate-pulse rounded-lg p-4" :class="notif.type === 'exit' ? 'bg-red-100 dark:bg-red-900 border-l-4 border-red-500' : 'bg-green-100 dark:bg-green-900 border-l-4 border-green-500'">
                <p class="font-semibold" :class="notif.type === 'exit' ? 'text-red-800 dark:text-red-200' : 'text-green-800 dark:text-green-200'">
                    <span x-text="notif.message"></span>
                </p>
                <p class="text-xs mt-1 opacity-75" :class="notif.type === 'exit' ? 'text-red-700 dark:text-red-300' : 'text-green-700 dark:text-green-300'" x-text="'قبل لحظات'"></p>
            </div>
        </template>
    </div>

    <!-- Main Content Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Recent Movements (Main - Larger) -->
        <div class="lg:col-span-2 bg-white dark:bg-slate-800 rounded-lg shadow p-6">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-xl font-bold text-gray-900 dark:text-white">📋 آخر الحركات</h2>
                <span class="text-sm text-gray-500 dark:text-gray-400">تحديث مباشر</span>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="border-b-2 border-gray-200 dark:border-slate-700">
                        <tr>
                            <th class="text-right py-3 px-2 font-semibold text-gray-700 dark:text-gray-300">النوع</th>
                            <th class="text-right py-3 px-2 font-semibold text-gray-700 dark:text-gray-300">الوسيلة</th>
                            <th class="text-right py-3 px-2 font-semibold text-gray-700 dark:text-gray-300">المخرج</th>
                            <th class="text-right py-3 px-2 font-semibold text-gray-700 dark:text-gray-300">المشغل</th>
                            <th class="text-right py-3 px-2 font-semibold text-gray-700 dark:text-gray-300">الوقت</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-slate-700">
                        <template x-for="movement in recentMovements.slice(0, 8)" :key="movement.id">
                            <tr class="hover:bg-gray-50 dark:hover:bg-slate-700/50 transition" :class="movement.type_class === 'exit' ? 'border-l-4 border-red-500' : 'border-l-4 border-green-500'">
                                <td class="py-3 px-2">
                                    <span class="inline-block px-3 py-1 rounded-full text-xs font-bold" :class="movement.type_class === 'exit' ? 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200' : 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200'">
                                        <span x-text="movement.type"></span>
                                    </span>
                                </td>
                                <td class="py-3 px-2 font-medium text-gray-900 dark:text-white" x-text="movement.vessel"></td>
                                <td class="py-3 px-2 text-gray-700 dark:text-gray-300" x-text="movement.exit"></td>
                                <td class="py-3 px-2 text-gray-700 dark:text-gray-300" x-text="movement.operator"></td>
                                <td class="py-3 px-2 font-mono text-gray-500 dark:text-gray-400">
                                    <span x-text="movement.timestamp" class="block text-xs"></span>
                                    <span x-text="movement.time" class="block text-xs opacity-70"></span>
                                </td>
                            </tr>
                        </template>
                    </tbody>
                </table>

                <div x-show="recentMovements.length === 0" class="text-center py-8 text-gray-500 dark:text-gray-400">
                    لا توجد حركات بعد
                </div>
            </div>
        </div>

        <!-- Active Vessels List -->
        <div class="bg-white dark:bg-slate-800 rounded-lg shadow p-6">
            <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-4">⚓ الوسائل النشطة</h2>

            <div class="space-y-2 max-h-96 overflow-y-auto">
                <template x-for="vessel in activeVesselsList" :key="vessel.id">
                    <div class="p-3 rounded-lg bg-gradient-to-r from-blue-50 to-blue-100 dark:from-slate-700 dark:to-slate-600 border-r-4 border-blue-500">
                        <p class="font-bold text-gray-900 dark:text-white text-sm" x-text="vessel.name"></p>
                        <div class="flex justify-between items-center mt-2">
                            <span class="text-xs text-gray-600 dark:text-gray-300">
                                <span class="text-gray-500">رقم:</span>
                                <span x-text="vessel.number"></span>
                            </span>
                            <span class="text-xs text-gray-500 dark:text-gray-400" x-text="vessel.last_activity"></span>
                        </div>
                    </div>
                </template>

                <div x-show="activeVesselsList.length === 0" class="text-center py-6 text-gray-500 dark:text-gray-400">
                    لا توجد وسائل نشطة
                </div>
            </div>
        </div>
    </div>

    <!-- Top Exits and Hour Stats -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Top Exits -->
        <div class="bg-white dark:bg-slate-800 rounded-lg shadow p-6">
            <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-4">🚪 أكثر المخارج نشاطاً</h2>

            <div class="space-y-3">
                <template x-for="exit in topExits" :key="exit.name">
                    <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-slate-700 rounded">
                        <span class="font-medium text-gray-900 dark:text-white" x-text="exit.name"></span>
                        <span class="bg-orange-100 dark:bg-orange-900 text-orange-800 dark:text-orange-200 px-3 py-1 rounded-full text-sm font-bold" x-text="exit.count + ' حركة'"></span>
                    </div>
                </template>

                <div x-show="topExits.length === 0" class="text-center py-6 text-gray-500 dark:text-gray-400">
                    لا توجد بيانات
                </div>
            </div>
        </div>

        <!-- Hour Stats -->
        <div class="bg-white dark:bg-slate-800 rounded-lg shadow p-6">
            <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-4">📊 إحصائيات الساعة الأخيرة</h2>

            <div class="space-y-4">
                <div class="p-4 bg-gradient-to-r from-red-50 to-red-100 dark:from-slate-700 dark:to-slate-600 rounded">
                    <div class="flex justify-between items-center">
                        <span class="text-gray-700 dark:text-gray-300 font-medium">🚀 خروجات</span>
                        <span class="text-2xl font-bold text-red-600 dark:text-red-400" x-text="hourStats.exits || 0"></span>
                    </div>
                </div>

                <div class="p-4 bg-gradient-to-r from-green-50 to-green-100 dark:from-slate-700 dark:to-slate-600 rounded">
                    <div class="flex justify-between items-center">
                        <span class="text-gray-700 dark:text-gray-300 font-medium">📥 دخولات</span>
                        <span class="text-2xl font-bold text-green-600 dark:text-green-400" x-text="hourStats.entries || 0"></span>
                    </div>
                </div>

                <div class="p-4 bg-gradient-to-r from-purple-50 to-purple-100 dark:from-slate-700 dark:to-slate-600 rounded">
                    <div class="flex justify-between items-center">
                        <span class="text-gray-700 dark:text-gray-300 font-medium">📦 إجمالي</span>
                        <span class="text-2xl font-bold text-purple-600 dark:text-purple-400" x-text="hourStats.total || 0"></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
<script>
    function liveDashboard() {
        return {
            activeVessels: 0,
            outsideVessels: 0,
            totalVessels: 0,
            archivedVessels: 0,
            operationalVessels: 0,
            maintenanceVessels: 0,
            outOfServiceVessels: 0,
            occupancyRate: 0,
            recentMovements: [],
            activeVesselsList: [],
            topExits: [],
            hourStats: { total: 0, exits: 0, entries: 0 },
            notifications: [],
            pollingInterval: null,
            movementPollingInterval: null,

            init() {
                this.fetchData();
                this.checkNewMovements();
                this.pollingInterval = setInterval(() => this.fetchData(), 3000);
                this.movementPollingInterval = setInterval(() => this.checkNewMovements(), 2000);
            },

            async fetchData() {
                try {
                    const response = await fetch('{{ route("live-dashboard.api") }}');
                    const data = await response.json();

                    // Update all values
                    this.activeVessels = data.activeVessels;
                    this.outsideVessels = data.outsideVessels;
                    this.totalVessels = data.totalVessels;
                    this.archivedVessels = data.archivedVessels;
                    this.operationalVessels = data.operationalVessels;
                    this.maintenanceVessels = data.maintenanceVessels;
                    this.outOfServiceVessels = data.outOfServiceVessels;
                    this.occupancyRate = data.occupancyRate;
                    this.recentMovements = data.recentMovements;
                    this.activeVesselsList = data.activeVesselsList;
                    this.topExits = data.topExits;
                    this.hourStats = data.hourStats;
                } catch (error) {
                    console.error('Error fetching live data:', error);
                }
            },

            async checkNewMovements() {
                try {
                    const response = await fetch('{{ route("live-dashboard.movements") }}');
                    const data = await response.json();

                    if (data.movements && data.movements.length > 0) {
                        data.movements.forEach(movement => {
                            this.notifications.unshift({
                                id: movement.id,
                                type: movement.type,
                                message: movement.message,
                                timestamp: new Date()
                            });
                        });

                        this.notifications = this.notifications.slice(0, 10);
                        this.playNotificationSound();

                        setTimeout(() => {
                            this.notifications = this.notifications.slice(data.movements.length);
                        }, 5000);
                    }
                } catch (error) {
                    console.error('Error checking movements:', error);
                }
            },

            playNotificationSound() {
                try {
                    const audioContext = new (window.AudioContext || window.webkitAudioContext)();
                    const oscillator = audioContext.createOscillator();
                    const gainNode = audioContext.createGain();

                    oscillator.connect(gainNode);
                    gainNode.connect(audioContext.destination);

                    oscillator.frequency.value = 800;
                    oscillator.type = 'sine';

                    gainNode.gain.setValueAtTime(0.3, audioContext.currentTime);
                    gainNode.gain.exponentialRampToValueAtTime(0.01, audioContext.currentTime + 0.5);

                    oscillator.start(audioContext.currentTime);
                    oscillator.stop(audioContext.currentTime + 0.5);
                } catch (e) {
                    // Silently fail if audio context not available
                }
            },

            destroy() {
                clearInterval(this.pollingInterval);
                clearInterval(this.movementPollingInterval);
            }
        }
    }

    function liveTime() {
        return {
            time: new Date().toLocaleTimeString('ar-SA'),

            init() {
                setInterval(() => {
                    this.time = new Date().toLocaleTimeString('ar-SA');
                }, 1000);
            }
        }
    }
</script>
@endsection
