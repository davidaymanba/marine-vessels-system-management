<x-app-layout>
    <div class="space-y-6">
        <div class="flex items-center justify-between">
            <h1 class="text-2xl font-bold text-slate-900">المخارج</h1>
            <a href="{{ route('exits.create') }}" class="rounded-xl bg-[#0EA5E9] px-5 py-3 text-white">إضافة مخرج</a>
        </div>

        <div class="rounded-2xl bg-white shadow-sm border border-slate-200 overflow-hidden">
            <table class="min-w-full text-sm">
                <thead class="bg-slate-50 text-slate-500">
                    <tr>
                        <th class="px-6 py-3 text-right">الاسم</th>
                        <th class="px-6 py-3 text-right">الحالة</th>
                        <th class="px-6 py-3 text-right">إجراءات</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($exits as $exit)
                        <tr>
                            <td class="px-6 py-4 font-medium">{{ $exit->name }}</td>
                            <td class="px-6 py-4">{{ $exit->is_active ? 'مفعل' : 'معطل' }}</td>
                            <td class="px-6 py-4 space-x-2 rtl:space-x-reverse">
                                <a href="{{ route('exits.edit', $exit) }}" class="text-amber-600">تعديل</a>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="3" class="px-6 py-8 text-center text-slate-500">لا توجد مخارج.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{ $exits->links() }}
    </div>
</x-app-layout>
