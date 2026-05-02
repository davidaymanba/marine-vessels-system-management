<x-app-layout>
    <div class="space-y-6">
        <div>
            <h1 class="text-2xl font-bold text-slate-900">سجل الحركات</h1>
        </div>

        <form method="GET" class="rounded-2xl bg-white p-4 shadow-sm border border-slate-200 grid gap-4 md:grid-cols-2 xl:grid-cols-5">
            <select name="type" class="rounded-xl border-slate-300 px-4 py-3">
                <option value="">كل الأنواع</option>
                <option value="exit" @selected(request('type') === 'exit')>خروج</option>
                <option value="entry" @selected(request('type') === 'entry')>دخول</option>
            </select>
            <select name="exit_id" class="rounded-xl border-slate-300 px-4 py-3">
                <option value="">كل المخارج</option>
                @foreach($exits as $exit)
                    <option value="{{ $exit->id }}" @selected((string)request('exit_id') === (string)$exit->id)>{{ $exit->name }}</option>
                @endforeach
            </select>
            <select name="vessel_id" class="rounded-xl border-slate-300 px-4 py-3">
                <option value="">كل الوسائل</option>
                @foreach($vessels as $vessel)
                    <option value="{{ $vessel->id }}" @selected((string)request('vessel_id') === (string)$vessel->id)>{{ $vessel->name }}</option>
                @endforeach
            </select>
            <input type="date" name="date_from" value="{{ request('date_from') }}" class="rounded-xl border-slate-300 px-4 py-3">
            <input type="date" name="date_to" value="{{ request('date_to') }}" class="rounded-xl border-slate-300 px-4 py-3">
            <button class="rounded-xl bg-slate-900 px-5 py-3 text-white md:col-span-2 xl:col-span-5">تصفية</button>
        </form>

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
                                <td class="px-6 py-4 font-medium">{{ $movement->vessel?->name }}</td>
                                <td class="px-6 py-4">
                                    <span class="inline-flex rounded-full px-3 py-1 text-xs font-semibold {{ $movement->type === 'exit' ? 'bg-amber-100 text-amber-700' : 'bg-emerald-100 text-emerald-700' }}">{{ $movement->type === 'exit' ? 'خروج' : 'دخول' }}</span>
                                </td>
                                <td class="px-6 py-4">{{ $movement->exit?->name ?? '-' }}</td>
                                <td class="px-6 py-4">{{ $movement->user?->name }}</td>
                                <td class="px-6 py-4">{{ optional($movement->moved_at)->format('Y-m-d H:i:s') }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="px-6 py-8 text-center text-slate-500">لا توجد حركات.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{ $movements->links() }}
    </div>
</x-app-layout>
