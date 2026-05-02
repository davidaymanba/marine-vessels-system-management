<x-app-layout>
    <div class="max-w-3xl space-y-6">
        <div>
            <h1 class="text-2xl font-bold text-slate-900">تعديل وسيلة</h1>
        </div>

        <form method="POST" action="{{ route('vessels.update', $vessel) }}" enctype="multipart/form-data" class="rounded-2xl bg-white p-6 shadow-sm border border-slate-200 space-y-4">
            @csrf
            @method('PUT')
            <div>
                <label class="block text-sm font-medium mb-2">اسم الوسيلة</label>
                <input type="text" name="name" class="w-full rounded-xl border-slate-300 px-4 py-3" value="{{ old('name', $vessel->name) }}" required>
            </div>
            <div>
                <label class="block text-sm font-medium mb-2">رقم الوسيلة</label>
                <input type="text" name="vessel_number" class="w-full rounded-xl border-slate-300 px-4 py-3" value="{{ old('vessel_number', $vessel->vessel_number) }}" required>
            </div>
            <div>
                <label class="block text-sm font-medium mb-2">الوصف</label>
                <textarea name="description" rows="4" class="w-full rounded-xl border-slate-300 px-4 py-3">{{ old('description', $vessel->description) }}</textarea>
            </div>
            <div>
                <label class="block text-sm font-medium mb-2">صورة جديدة</label>
                <input type="file" name="image" class="w-full rounded-xl border-slate-300 px-4 py-3">
            </div>
            <button class="rounded-xl bg-[#0EA5E9] px-5 py-3 text-white font-medium">حفظ التعديلات</button>
        </form>
    </div>
</x-app-layout>
