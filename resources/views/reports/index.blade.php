<x-app-layout>
    <div class="space-y-6 max-w-5xl">
        <div>
            <h1 class="text-2xl font-bold text-slate-900">التقارير</h1>
        </div>

        <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-4">
            <a href="{{ route('reports.daily') }}" class="rounded-2xl bg-white p-6 shadow-sm border border-slate-200 hover:border-sky-300">
                <div class="text-lg font-semibold">يومي</div>
                <div class="mt-1 text-sm text-slate-500">عرض حركات اليوم</div>
            </a>
            <a href="{{ route('reports.weekly') }}" class="rounded-2xl bg-white p-6 shadow-sm border border-slate-200 hover:border-sky-300">
                <div class="text-lg font-semibold">أسبوعي</div>
                <div class="mt-1 text-sm text-slate-500">ملخص الأسبوع الحالي</div>
            </a>
            <a href="{{ route('reports.monthly') }}" class="rounded-2xl bg-white p-6 shadow-sm border border-slate-200 hover:border-sky-300">
                <div class="text-lg font-semibold">شهري</div>
                <div class="mt-1 text-sm text-slate-500">ملخص الشهر الحالي</div>
            </a>
            <div class="rounded-2xl bg-white p-6 shadow-sm border border-slate-200">
                <div class="text-lg font-semibold">مخصص</div>
                <form method="GET" action="{{ route('reports.custom') }}" class="mt-4 space-y-3">
                    <input type="date" name="date_from" class="w-full rounded-xl border-slate-300 px-4 py-3" required>
                    <input type="date" name="date_to" class="w-full rounded-xl border-slate-300 px-4 py-3" required>
                    <button class="w-full rounded-xl bg-slate-900 px-5 py-3 text-white">عرض التقرير</button>
                </form>
            </div>
        </div>

        <div class="rounded-2xl bg-white p-6 shadow-sm border border-slate-200">
            <h2 class="font-bold text-slate-900 mb-4">تقارير حسب الوسيلة أو المخرج</h2>
            <div class="grid gap-4 md:grid-cols-2">
                <div>
                    <div class="text-sm font-medium mb-2">الوسيلة</div>
                    <div class="space-y-2">
                        @foreach($vessels as $vessel)
                            <a href="{{ route('reports.vessel', $vessel->id) }}" class="block rounded-xl bg-slate-50 px-4 py-3 hover:bg-slate-100">{{ $vessel->name }}</a>
                        @endforeach
                    </div>
                </div>
                <div>
                    <div class="text-sm font-medium mb-2">المخرج</div>
                    <div class="space-y-2">
                        @foreach($exits as $exit)
                            <a href="{{ route('reports.exit', $exit->id) }}" class="block rounded-xl bg-slate-50 px-4 py-3 hover:bg-slate-100">{{ $exit->name }}</a>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
