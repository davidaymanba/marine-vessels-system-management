<x-app-layout>
    <div class="max-w-3xl space-y-6">
        <div>
            <h1 class="text-2xl font-bold text-slate-900">إضافة وسيلة بحرية</h1>
            <p class="text-slate-500 mt-1">أدخل بيانات الوسيلة وسيتم توليد الباركود تلقائياً.</p>
        </div>

        <form method="POST" action="{{ route('vessels.store') }}" enctype="multipart/form-data" class="rounded-2xl bg-white p-6 shadow-sm border border-slate-200 space-y-4">
            @csrf
            <div>
                <label class="block text-sm font-medium mb-2">اسم الوسيلة</label>
                <input type="text" name="name" class="w-full rounded-xl border-slate-300 px-4 py-3" value="{{ old('name') }}" required>
            </div>
            <div>
                <label class="block text-sm font-medium mb-2">رقم الوسيلة</label>
                <input type="text" name="vessel_number" class="w-full rounded-xl border-slate-300 px-4 py-3" value="{{ old('vessel_number') }}" required>
            </div>
            <div>
                <label class="block text-sm font-medium mb-2">نوع الوسيلة</label>
                <input type="text" name="vessel_type" class="w-full rounded-xl border-slate-300 px-4 py-3" value="{{ old('vessel_type') }}" placeholder="مثال: Tug / Ferry / Workboat">
            </div>
            <div>
                <label class="block text-sm font-medium mb-2">المالك</label>
                <input type="text" name="owner_name" class="w-full rounded-xl border-slate-300 px-4 py-3" value="{{ old('owner_name') }}">
            </div>
            <div>
                <label class="block text-sm font-medium mb-2">السعة</label>
                <input type="number" name="capacity" min="1" class="w-full rounded-xl border-slate-300 px-4 py-3" value="{{ old('capacity') }}" placeholder="عدد الأفراد أو الحمولة">
            </div>
            <div>
                <label class="block text-sm font-medium mb-2">حالة الصيانة</label>
                <select name="maintenance_status" class="w-full rounded-xl border-slate-300 px-4 py-3">
                    <option value="operational" @selected(old('maintenance_status', 'operational') === 'operational')>تشغيلية</option>
                    <option value="maintenance" @selected(old('maintenance_status') === 'maintenance')>في الصيانة</option>
                    <option value="out_of_service" @selected(old('maintenance_status') === 'out_of_service')>خارج الخدمة</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium mb-2">الوصف</label>
                <textarea name="description" rows="4" class="w-full rounded-xl border-slate-300 px-4 py-3">{{ old('description') }}</textarea>
            </div>
            <div>
                <label class="block text-sm font-medium mb-2">صورة</label>
                <input type="file" name="image" class="w-full rounded-xl border-slate-300 px-4 py-3">
            </div>
            <button class="rounded-xl bg-[#0EA5E9] px-5 py-3 text-white font-medium">حفظ الوسيلة</button>
        </form>
    </div>
</x-app-layout>
