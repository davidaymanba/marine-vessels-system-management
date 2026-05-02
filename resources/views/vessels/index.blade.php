<x-app-layout>
    <div class="space-y-6">
        <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
            <div>
                <h1 class="text-2xl font-bold text-slate-900">الوسائل البحرية</h1>
                <p class="text-slate-500 mt-1">إدارة الوسائل وفلاتر الحالة وطباعة الباركود.</p>
            </div>
            <a href="{{ route('vessels.create') }}" class="inline-flex items-center justify-center rounded-xl bg-[#0EA5E9] px-5 py-3 text-white font-medium hover:bg-sky-500">إضافة وسيلة</a>
        </div>

        <form method="GET" class="rounded-2xl bg-white p-4 shadow-sm border border-slate-200 flex flex-col gap-4 lg:flex-row">
            <select name="status" class="rounded-xl border-slate-300 px-4 py-3">
                <option value="">كل الحالات</option>
                <option value="inside" @selected($status === 'inside')>داخل</option>
                <option value="outside" @selected($status === 'outside')>خارج</option>
            </select>
            <button class="rounded-xl bg-slate-900 px-5 py-3 text-white">تصفية</button>
        </form>

        <div class="rounded-2xl bg-white shadow-sm border border-slate-200 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead class="bg-slate-50 text-slate-500">
                        <tr>
                            <th class="px-6 py-3 text-right">الاسم</th>
                            <th class="px-6 py-3 text-right">الرقم</th>
                            <th class="px-6 py-3 text-right">الباركود</th>
                            <th class="px-6 py-3 text-right">الحالة</th>
                            <th class="px-6 py-3 text-right">إجراءات</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($vessels as $vessel)
                            <tr>
                                <td class="px-6 py-4 font-semibold">{{ $vessel->name }}</td>
                                <td class="px-6 py-4">{{ $vessel->vessel_number }}</td>
                                <td class="px-6 py-4">{{ $vessel->barcode }}</td>
                                <td class="px-6 py-4">
                                    <span class="inline-flex rounded-full px-3 py-1 text-xs font-semibold {{ $vessel->status === 'inside' ? 'bg-emerald-100 text-emerald-700' : 'bg-rose-100 text-rose-700' }}">
                                        {{ $vessel->status === 'inside' ? 'داخل' : 'خارج' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 space-x-2 rtl:space-x-reverse">
                                    <a href="{{ route('vessels.show', $vessel) }}" class="text-sky-600 hover:underline">عرض</a>
                                    <a href="{{ route('vessels.edit', $vessel) }}" class="text-amber-600 hover:underline">تعديل</a>
                                    <a href="{{ route('vessels.barcode', $vessel) }}" class="text-slate-600 hover:underline">باركود</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-10 text-center text-slate-500">لا توجد وسائل.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{ $vessels->links() }}
    </div>
</x-app-layout>
