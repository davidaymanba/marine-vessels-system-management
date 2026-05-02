<x-app-layout>
    <div class="space-y-6">
        <div>
            <h1 class="text-2xl font-bold text-slate-900">سجل التدقيق</h1>
            <p class="text-slate-500 mt-1">تتبع جميع العمليات الإدارية والتشغيلية.</p>
        </div>

        <form method="GET" class="rounded-2xl bg-white p-4 shadow-sm border border-slate-200 grid gap-4 md:grid-cols-2 xl:grid-cols-4">
            <select name="action" class="rounded-xl border-slate-300 px-4 py-3">
                <option value="">كل العمليات</option>
                @foreach($actions as $action)
                    <option value="{{ $action }}" @selected(request('action') === $action)>{{ $action }}</option>
                @endforeach
            </select>
            <select name="subject_type" class="rounded-xl border-slate-300 px-4 py-3">
                <option value="">كل الأنواع</option>
                @foreach($subjectTypes as $subjectType)
                    <option value="{{ $subjectType }}" @selected(request('subject_type') === $subjectType)>{{ class_basename($subjectType) }}</option>
                @endforeach
            </select>
            <input type="date" name="date_from" value="{{ request('date_from') }}" class="rounded-xl border-slate-300 px-4 py-3">
            <input type="date" name="date_to" value="{{ request('date_to') }}" class="rounded-xl border-slate-300 px-4 py-3">
            <button class="rounded-xl bg-slate-900 px-5 py-3 text-white md:col-span-2 xl:col-span-4">تصفية</button>
        </form>

        <div class="rounded-2xl bg-white shadow-sm border border-slate-200 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead class="bg-slate-50 text-slate-500">
                        <tr>
                            <th class="px-6 py-3 text-right">الوقت</th>
                            <th class="px-6 py-3 text-right">المستخدم</th>
                            <th class="px-6 py-3 text-right">العملية</th>
                            <th class="px-6 py-3 text-right">الكيان</th>
                            <th class="px-6 py-3 text-right">الوصف</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($logs as $log)
                            <tr>
                                <td class="px-6 py-4 text-slate-500">{{ optional($log->created_at)->format('Y-m-d H:i:s') }}</td>
                                <td class="px-6 py-4">{{ $log->user?->name ?? 'System' }}</td>
                                <td class="px-6 py-4">
                                    <span class="inline-flex rounded-full px-3 py-1 text-xs font-semibold bg-sky-100 text-sky-700">
                                        {{ $log->action }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">{{ class_basename($log->subject_type) }} #{{ $log->subject_id }}</td>
                                <td class="px-6 py-4">{{ $log->description }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-8 text-center text-slate-500">لا توجد سجلات بعد.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{ $logs->links() }}
    </div>
</x-app-layout>
