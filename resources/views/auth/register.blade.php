<h2 class="text-2xl font-bold text-center text-gray-800 mb-2">إنشاء حساب جديد</h2>
<p class="text-center text-gray-600 text-sm mb-8">انضم إلينا الآن وابدأ إدارة الوسائل البحرية</p>

<form method="POST" action="{{ route('register') }}">
    @csrf

    <!-- Name Field -->
    <div class="form-group">
        <label for="name" class="form-label">الاسم الكامل</label>
        <input 
            id="name" 
            type="text" 
            name="name" 
            value="{{ old('name') }}"
            class="form-input"
            placeholder="أدخل اسمك الكامل"
            required
            autofocus
        />
        @error('name')
            <p class="text-red-500 text-xs mt-2">{{ $message }}</p>
        @enderror
    </div>

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
                placeholder="أدخل كلمة مرور قوية"
                required
            />
            <button 
                type="button" 
                class="password-toggle"
                onclick="togglePassword('password')"
            >
                👁️
            </button>
        </div>
        @error('password')
            <p class="text-red-500 text-xs mt-2">{{ $message }}</p>
        @enderror
    </div>

    <!-- Password Confirmation Field -->
    <div class="form-group">
        <label for="password_confirmation" class="form-label">تأكيد كلمة المرور</label>
        <div class="password-field">
            <input 
                id="password_confirmation" 
                type="password" 
                name="password_confirmation"
                class="form-input"
                placeholder="أعد إدخال كلمة المرور"
                required
            />
            <button 
                type="button" 
                class="password-toggle"
                onclick="togglePassword('password_confirmation')"
            >
                👁️
            </button>
        </div>
        @error('password_confirmation')
            <p class="text-red-500 text-xs mt-2">{{ $message }}</p>
        @enderror
    </div>

    <!-- Submit Button -->
    <button type="submit" class="btn-submit">
        <span>✨</span>
        <span>إنشاء حساب</span>
    </button>
</form>

<!-- Divider -->
<div class="divider">
    <span>أو</span>
</div>

<!-- Back to Login -->
<div class="text-center">
    <p class="text-sm text-gray-600">
        هل لديك حساب بالفعل؟ 
        <a href="{{ route('login') }}" class="text-purple-600 hover:text-purple-800 font-semibold transition">
            دخول الآن
        </a>
    </p>
</div>

<script>
    function togglePassword(fieldId) {
        const field = document.getElementById(fieldId);
        const btn = event.target;
        if (field.type === 'password') {
            field.type = 'text';
            btn.textContent = '🙈';
        } else {
            field.type = 'password';
            btn.textContent = '👁️';
        }
    }
</script>
