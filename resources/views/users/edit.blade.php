<x-app-layout>
    <div class="max-w-3xl space-y-6">
        <h1 class="text-2xl font-bold text-slate-900">تعديل مستخدم</h1>
        <form method="POST" action="{{ route('users.update', $user) }}" class="rounded-2xl bg-white p-6 shadow-sm border border-slate-200 space-y-4">
            @csrf
            @method('PUT')
            <div>
                <label class="block text-sm font-medium mb-2">الاسم</label>
                <input name="name" class="w-full rounded-xl border-slate-300 px-4 py-3" value="{{ old('name', $user->name) }}" required>
            </div>
            <div>
                <label class="block text-sm font-medium mb-2">البريد</label>
                <input type="email" name="email" class="w-full rounded-xl border-slate-300 px-4 py-3" value="{{ old('email', $user->email) }}" required>
            </div>
            <div>
                <label class="block text-sm font-medium mb-2">رقم WhatsApp</label>
                <input type="text" name="phone" class="w-full rounded-xl border-slate-300 px-4 py-3" value="{{ old('phone', $user->phone) }}" placeholder="201001234567">
            </div>
            <div>
                <label class="block text-sm font-medium mb-2">كلمة المرور الجديدة</label>
                <input type="password" name="password" class="w-full rounded-xl border-slate-300 px-4 py-3">
            </div>
            <div>
                <label class="block text-sm font-medium mb-2">تأكيد كلمة المرور</label>
                <input type="password" name="password_confirmation" class="w-full rounded-xl border-slate-300 px-4 py-3">
            </div>
            <div>
                <label class="block text-sm font-medium mb-2">الدور</label>
                <select name="role" class="w-full rounded-xl border-slate-300 px-4 py-3">
                    <option value="operator" @selected($user->role === 'operator')>operator</option>
                    <option value="supervisor" @selected($user->role === 'supervisor')>supervisor</option>
                    <option value="admin" @selected($user->role === 'admin')>admin</option>
                </select>
            </div>
            <button class="rounded-xl bg-[#0EA5E9] px-5 py-3 text-white">تحديث</button>
        </form>
    </div>
</x-app-layout>
