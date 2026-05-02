<x-app-layout>
    <div class="max-w-3xl space-y-6" x-data="barcodeScanner()" x-init="init()">
        <div>
            <h1 class="text-2xl font-bold text-slate-900">مسح الباركود للدخول</h1>
            <p class="text-slate-500 mt-1">ضع المؤشر داخل الحقل وامسح الباركود مباشرة.</p>
        </div>

        <form method="POST" action="{{ route('movements.checkin') }}" class="rounded-2xl bg-white p-6 shadow-sm border border-slate-200 space-y-4" @submit="loading = true">
            @csrf
            <div>
                <label class="block text-sm font-medium mb-2">الباركود</label>
                <input id="barcode-input" x-ref="input" type="text" name="barcode" autocomplete="off" class="w-full rounded-2xl border-slate-300 px-5 py-5 text-2xl tracking-widest font-mono" x-model="barcode" @keydown.enter.prevent="submitNow()" @keyup="queueSubmit()" autofocus>
            </div>
            <button type="submit" class="rounded-xl bg-slate-900 px-5 py-3 text-white font-medium" :disabled="loading">
                <span x-show="!loading">إرسال</span>
                <span x-show="loading">جاري المعالجة...</span>
            </button>
            <div x-show="message" class="rounded-xl px-4 py-3 text-sm" :class="messageType === 'success' ? 'bg-emerald-50 text-emerald-700' : 'bg-rose-50 text-rose-700'" x-text="message"></div>
        </form>

        <script>
            function barcodeScanner() {
                return {
                    barcode: '',
                    timer: null,
                    loading: false,
                    message: '',
                    messageType: 'success',
                    init() {
                        this.focusInput();
                        window.addEventListener('load', () => this.focusInput());
                        setInterval(() => this.focusInput(), 1000);
                    },
                    focusInput() {
                        const input = this.$refs.input || document.getElementById('barcode-input');
                        if (input && document.activeElement !== input) {
                            input.focus();
                        }
                    },
                    queueSubmit() {
                        this.message = '';
                        clearTimeout(this.timer);
                        this.timer = setTimeout(() => this.submitNow(), 500);
                    },
                    submitNow() {
                        if (!this.barcode) return;
                        this.loading = true;
                        this.$root.querySelector('form').requestSubmit();
                    },
                };
            }
        </script>
    </div>
</x-app-layout>
