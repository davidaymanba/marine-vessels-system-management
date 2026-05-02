<x-app-layout>
    <div class="max-w-3xl space-y-6">
        <h1 class="text-2xl font-bold text-slate-900">إضافة مستخدم</h1>
        <form method="POST" action="{{ route('users.store') }}" class="rounded-2xl bg-white p-6 shadow-sm border border-slate-200 space-y-4">
            @csrf
            <div>
                <label class="block text-sm font-medium mb-2">الاسم</label>
                <input name="name" class="w-full rounded-xl border-slate-300 px-4 py-3" required>
            </div>
            <div>
                <label class="block text-sm font-medium mb-2">البريد</label>
                <input type="email" name="email" class="w-full rounded-xl border-slate-300 px-4 py-3" required>
            </div>
            <div>
                <label class="block text-sm font-medium mb-2">رقم WhatsApp</label>
                <input type="text" name="phone" class="w-full rounded-xl border-slate-300 px-4 py-3" placeholder="201001234567">
            </div>
            <div>
                <label class="block text-sm font-medium mb-2">كلمة المرور</label>
                <input type="password" name="password" class="w-full rounded-xl border-slate-300 px-4 py-3" required>
            </div>
            <div>
                <label class="block text-sm font-medium mb-2">تأكيد كلمة المرور</label>
                <input type="password" name="password_confirmation" class="w-full rounded-xl border-slate-300 px-4 py-3" required>
            </div>
            <div>
                <label class="block text-sm font-medium mb-2">الدور</label>
                <select name="role" class="w-full rounded-xl border-slate-300 px-4 py-3">
                    <option value="operator">operator</option>
                    <option value="admin">admin</option>
                </select>
            </div>
            <button class="rounded-xl bg-[#0EA5E9] px-5 py-3 text-white">حفظ</button>
        </form>
    </div>
</x-app-layout>
