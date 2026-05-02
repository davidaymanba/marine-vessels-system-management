<h2 class="text-2xl font-bold text-center text-gray-800 mb-2">استعادة كلمة المرور</h2>
<p class="text-center text-gray-600 text-sm mb-8">لا تقلق! أدخل بريدك الإلكتروني وسنرسل لك رابط إعادة التعيين</p>

<!-- Session Status -->
@if (session('status'))
    <div class="error-alert">
        <span>✓</span>
        <span>{{ session('status') }}</span>
    </div>
@endif

<form method="POST" action="{{ route('password.email') }}">
    @csrf

    <!-- Email Field -->
    <div class="form-group">
        <label for="email" class="form-label">البريد الإلكتروني</label>
        <input 
            id="email" 
            type="email" 
            name="email" 
            value="{{ old('email') }}"
            class="form-input"
            placeholder="أدخل بريدك الإلكتروني المسجل"
            required
            autofocus
        />
        @error('email')
            <p class="text-red-500 text-xs mt-2">{{ $message }}</p>
        @enderror
    </div>

    <!-- Submit Button -->
    <button type="submit" class="btn-submit">
        <span>📧</span>
        <span>إرسال رابط إعادة التعيين</span>
    </button>
</form>

<!-- Back Link -->
<div class="text-center mt-6">
    <a href="{{ route('login') }}" class="text-sm text-gray-600 hover:text-purple-600 transition">
        ← العودة لصفحة الدخول
    </a>
</div>
