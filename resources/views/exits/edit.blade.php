<x-app-layout>
    <div class="max-w-3xl space-y-6">
        <h1 class="text-2xl font-bold text-slate-900">تعديل مخرج</h1>
        <form method="POST" action="{{ route('exits.update', $exit) }}" class="rounded-2xl bg-white p-6 shadow-sm border border-slate-200 space-y-4">
            @csrf
            @method('PUT')
            <div>
                <label class="block text-sm font-medium mb-2">الاسم</label>
                <input name="name" class="w-full rounded-xl border-slate-300 px-4 py-3" value="{{ old('name', $exit->name) }}" required>
            </div>
            <div>
                <label class="block text-sm font-medium mb-2">الوصف</label>
                <textarea name="description" rows="4" class="w-full rounded-xl border-slate-300 px-4 py-3">{{ old('description', $exit->description) }}</textarea>
            </div>
            <label class="inline-flex items-center gap-2">
                <input type="checkbox" name="is_active" value="1" @checked($exit->is_active)>
                <span>مفعل</span>
            </label>
            <button class="rounded-xl bg-[#0EA5E9] px-5 py-3 text-white">تحديث</button>
        </form>
    </div>
</x-app-layout>
