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
            <select name="maintenance_status" class="rounded-xl border-slate-300 px-4 py-3">
                <option value="">كل حالات الصيانة</option>
                <option value="operational" @selected($maintenanceStatus === 'operational')>تشغيلية</option>
                <option value="maintenance" @selected($maintenanceStatus === 'maintenance')>في الصيانة</option>
                <option value="out_of_service" @selected($maintenanceStatus === 'out_of_service')>خارج الخدمة</option>
            </select>
            <select name="archived" class="rounded-xl border-slate-300 px-4 py-3">
                <option value="0" @selected(!$archived)>الوسائل النشطة</option>
                <option value="1" @selected($archived)>الأرشيف</option>
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
                            <th class="px-6 py-3 text-right">النوع</th>
                            <th class="px-6 py-3 text-right">المالك</th>
                            <th class="px-6 py-3 text-right">السعة</th>
                            <th class="px-6 py-3 text-right">الصيانة</th>
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
                                <td class="px-6 py-4">{{ $vessel->vessel_type ?? '-' }}</td>
                                <td class="px-6 py-4">{{ $vessel->owner_name ?? '-' }}</td>
                                <td class="px-6 py-4">{{ $vessel->capacity ?? '-' }}</td>
                                <td class="px-6 py-4">
                                    <span class="inline-flex rounded-full px-3 py-1 text-xs font-semibold {{ $vessel->maintenance_status === 'operational' ? 'bg-emerald-100 text-emerald-700' : ($vessel->maintenance_status === 'maintenance' ? 'bg-amber-100 text-amber-700' : 'bg-rose-100 text-rose-700') }}">
                                        {{ $vessel->maintenance_status === 'operational' ? 'تشغيلية' : ($vessel->maintenance_status === 'maintenance' ? 'في الصيانة' : 'خارج الخدمة') }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">{{ $vessel->barcode }}</td>
                                <td class="px-6 py-4">
                                    <span class="inline-flex rounded-full px-3 py-1 text-xs font-semibold {{ $vessel->status === 'inside' ? 'bg-emerald-100 text-emerald-700' : 'bg-rose-100 text-rose-700' }}">
                                        {{ $vessel->status === 'inside' ? 'داخل' : 'خارج' }}
                                    </span>
                                    @if($vessel->isArchived())
                                        <span class="mt-2 inline-flex rounded-full px-3 py-1 text-xs font-semibold bg-slate-200 text-slate-700">مؤرشفة</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 space-x-2 rtl:space-x-reverse">
                                    <a href="{{ route('vessels.show', $vessel) }}" class="text-sky-600 hover:underline">عرض</a>
                                    <a href="{{ route('vessels.edit', $vessel) }}" class="text-amber-600 hover:underline">تعديل</a>
                                    <a href="{{ route('vessels.barcode', $vessel) }}" class="text-slate-600 hover:underline">باركود</a>
                                    @if($vessel->isArchived())
                                        <form method="POST" action="{{ route('vessels.restore', $vessel) }}" class="inline">
                                            @csrf
                                            @method('PATCH')
                                            <button class="text-emerald-600 hover:underline">استعادة</button>
                                        </form>
                                    @else
                                        <form method="POST" action="{{ route('vessels.archive', $vessel) }}" class="inline">
                                            @csrf
                                            @method('PATCH')
                                            <button class="text-amber-600 hover:underline">أرشفة</button>
                                        </form>
                                    @endif
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
