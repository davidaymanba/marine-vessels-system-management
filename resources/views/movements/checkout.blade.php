<x-app-layout>
    <div class="max-w-3xl space-y-6">
        <div>
            <h1 class="text-2xl font-bold text-slate-900">تسجيل خروج وسيلة</h1>
        </div>

        <form method="POST" action="{{ route('movements.checkout.store') }}" class="rounded-2xl bg-white p-6 shadow-sm border border-slate-200 space-y-4">
            @csrf
            <div>
                <label class="block text-sm font-medium mb-2">الوسيلة</label>
                <select name="vessel_id" class="w-full rounded-xl border-slate-300 px-4 py-3" required>
                    <option value="">اختر وسيلة</option>
                    @foreach($vessels as $vessel)
                        <option value="{{ $vessel->id }}">{{ $vessel->name }} - {{ $vessel->vessel_number }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium mb-2">المخرج</label>
                <select name="exit_id" class="w-full rounded-xl border-slate-300 px-4 py-3" required>
                    <option value="">اختر مخرج</option>
                    @foreach($exits as $exit)
                        <option value="{{ $exit->id }}">{{ $exit->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium mb-2">ملاحظات</label>
                <textarea name="notes" rows="4" class="w-full rounded-xl border-slate-300 px-4 py-3"></textarea>
            </div>
            <button class="rounded-xl bg-[#0EA5E9] px-5 py-3 text-white font-medium">تسجيل الخروج</button>
        </form>
    </div>
</x-app-layout>
