<x-app-layout>
    <div class="space-y-6">
        <div class="flex items-center justify-between">
            <h1 class="text-2xl font-bold text-slate-900">المستخدمون</h1>
            <a href="{{ route('users.create') }}" class="rounded-xl bg-[#0EA5E9] px-5 py-3 text-white">إضافة مستخدم</a>
        </div>

        <div class="rounded-2xl bg-white shadow-sm border border-slate-200 overflow-hidden">
            <table class="min-w-full text-sm">
                <thead class="bg-slate-50 text-slate-500">
                    <tr>
                        <th class="px-6 py-3 text-right">الاسم</th>
                        <th class="px-6 py-3 text-right">البريد</th>
                        <th class="px-6 py-3 text-right">WhatsApp</th>
                        <th class="px-6 py-3 text-right">الدور</th>
                        <th class="px-6 py-3 text-right">إجراءات</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($users as $user)
                        <tr>
                            <td class="px-6 py-4 font-medium">{{ $user->name }}</td>
                            <td class="px-6 py-4">{{ $user->email }}</td>
                            <td class="px-6 py-4">{{ $user->phone ?? '-' }}</td>
                            <td class="px-6 py-4">{{ $user->role }}</td>
                            <td class="px-6 py-4 space-x-2 rtl:space-x-reverse">
                                <a href="{{ route('users.edit', $user) }}" class="text-amber-600">تعديل</a>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="px-6 py-8 text-center text-slate-500">لا توجد مستخدمون.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{ $users->links() }}
    </div>
</x-app-layout>
