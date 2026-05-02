<x-guest-layout>
<h2 class="text-2xl font-bold text-center text-gray-800 mb-2">تسجيل الدخول</h2>
<p class="text-center text-gray-600 text-sm mb-8">أدخل بيانات حسابك للدخول للنظام</p>

<!-- Session Status -->
@if (session('status'))
    <div class="error-alert">
        <span>✓</span>
        <span>{{ session('status') }}</span>
    </div>
@endif

<form method="POST" action="{{ route('login') }}">
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
            placeholder="أدخل بريدك الإلكتروني"
            required
            autofocus
        />
        @error('email')
            <p class="text-red-500 text-xs mt-2">{{ $message }}</p>
        @enderror
    </div>

    <!-- Password Field -->
    <div class="form-group">
        <label for="password" class="form-label">كلمة المرور</label>
        <div class="password-field">
            <input 
                id="password" 
                type="password" 
                name="password"
                class="form-input"
                placeholder="أدخل كلمة المرور"
                required
            />
            <button 
                type="button" 
                class="password-toggle"
                onclick="togglePassword(this)"
            >
                👁️
            </button>
        </div>
        @error('password')
            <p class="text-red-500 text-xs mt-2">{{ $message }}</p>
        @enderror
    </div>

    <!-- Remember Me & Forgot Password -->
    <div class="checkbox-group">
        <label class="checkbox-wrapper">
            <input 
                type="checkbox" 
                name="remember"
                class="checkbox-input"
            />
            <span class="checkbox-label">تذكرني</span>
        </label>

        @if (Route::has('password.request'))
            <a href="{{ route('password.request') }}" class="forgot-password">
                هل نسيت كلمة المرور؟
            </a>
        @endif
    </div>

    <!-- Submit Button -->
    <button type="submit" class="btn-submit">
        <span>🔐</span>
        <span>دخول</span>
    </button>
</form>

<!-- Divider -->
<div class="divider">
    <span>أو</span>
</div>

<!-- Demo Credentials -->
<div class="demo-box">
    <div class="demo-title">📌 بيانات التجربة</div>
    
    <div class="demo-item" onclick="copyToClipboard('admin@system.com')">
        <span class="demo-code">admin@system.com</span>
        <span class="copy-btn">📋</span>
    </div>
    
    <div class="demo-item" onclick="copyToClipboard('admin123')">
        <span class="demo-code">admin123</span>
        <span class="copy-btn">📋</span>
    </div>
</div>

<script>
    function togglePassword(btn) {
        const passwordInput = document.getElementById('password');
        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            btn.textContent = '🙈';
        } else {
            passwordInput.type = 'password';
            btn.textContent = '👁️';
        }
    }

    function copyToClipboard(text) {
        navigator.clipboard.writeText(text).then(() => {
            alert('تم النسخ: ' + text);
        }).catch(() => {
            alert('فشل النسخ');
        });
    }
</script>
</x-guest-layout>
