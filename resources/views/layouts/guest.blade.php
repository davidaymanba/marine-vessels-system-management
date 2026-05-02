<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Marine Vessels Management') }}</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@300;400;500;700;800&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        html, body {
            height: 100%;
        }

        body {
            font-family: 'Tajawal', sans-serif;
            background:
                radial-gradient(circle at top right, rgba(255, 255, 255, 0.16), transparent 30%),
                radial-gradient(circle at bottom left, rgba(255, 255, 255, 0.12), transparent 28%),
                linear-gradient(135deg, #0f172a 0%, #1e3a8a 45%, #7c3aed 100%);
        }

        .auth-shell {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 24px;
            position: relative;
            overflow: hidden;
        }

        .auth-shell::before,
        .auth-shell::after {
            content: '';
            position: absolute;
            border-radius: 9999px;
            filter: blur(60px);
            pointer-events: none;
            opacity: 0.7;
        }

        .auth-shell::before {
            width: 320px;
            height: 320px;
            background: rgba(255, 255, 255, 0.16);
            top: -80px;
            right: -100px;
        }

        .auth-shell::after {
            width: 240px;
            height: 240px;
            background: rgba(56, 189, 248, 0.16);
            bottom: -60px;
            left: -80px;
        }

        .auth-panel {
            position: relative;
            z-index: 1;
            width: 100%;
            max-width: 520px;
            animation: authRise 0.65s ease-out both;
        }

        .auth-brand {
            text-align: center;
            margin-bottom: 22px;
            animation: authFade 0.65s ease-out 0.12s both;
        }

        .auth-badge {
            width: 76px;
            height: 76px;
            margin: 0 auto 16px;
            border-radius: 24px;
            display: grid;
            place-items: center;
            background: rgba(255, 255, 255, 0.16);
            border: 1px solid rgba(255, 255, 255, 0.24);
            box-shadow: 0 18px 40px rgba(15, 23, 42, 0.24);
            backdrop-filter: blur(14px);
        }

        .auth-badge svg {
            width: 36px;
            height: 36px;
            color: #fff;
        }

        .auth-title {
            color: #fff;
            font-size: 29px;
            font-weight: 800;
            line-height: 1.2;
            margin-bottom: 6px;
        }

        .auth-subtitle {
            color: rgba(255, 255, 255, 0.88);
            font-size: 14px;
            line-height: 1.7;
        }

        .auth-card {
            background: rgba(255, 255, 255, 0.96);
            border: 1px solid rgba(255, 255, 255, 0.34);
            border-radius: 28px;
            box-shadow: 0 30px 80px rgba(15, 23, 42, 0.28);
            backdrop-filter: blur(24px);
            padding: 34px;
            color: #0f172a;
        }

        .auth-footer {
            text-align: center;
            margin-top: 16px;
            color: rgba(255, 255, 255, 0.78);
            font-size: 12px;
        }

        @keyframes authRise {
            from {
                opacity: 0;
                transform: translateY(26px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes authFade {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @media (max-width: 640px) {
            .auth-shell {
                padding: 16px;
            }

            .auth-panel {
                max-width: 100%;
            }

            .auth-title {
                font-size: 24px;
            }

            .auth-card {
                padding: 24px 20px;
                border-radius: 24px;
            }
        }
    </style>
</head>
<body>
    <main class="auth-shell">
        <section class="auth-panel">
            <div class="auth-brand">
                <div class="auth-badge" aria-hidden="true">
                    <svg viewBox="0 0 64 64" fill="none" aria-hidden="true">
                        <path d="M32 10v20" stroke="currentColor" stroke-width="3" stroke-linecap="round" />
                        <path d="M32 12L20 26h12V12Z" fill="currentColor" />
                        <path d="M32 12l12 14H32V12Z" fill="rgba(255,255,255,0.7)" />
                        <path d="M14 38h36l-6 10H20l-6-10Z" fill="currentColor" />
                        <path d="M18 50c4 3 8 4 14 4s10-1 14-4" stroke="currentColor" stroke-width="3" stroke-linecap="round" />
                    </svg>
                </div>
                <h1 class="auth-title">Marine Vessels</h1>
                <p class="auth-subtitle">نظام إدارة الوسائل البحرية بطريقة حديثة وواضحة</p>
            </div>

            <div class="auth-card">
                {{ $slot }}
            </div>

            <div class="auth-footer">
                <p>© 2026 جميع الحقوق محفوظة</p>
            </div>
        </section>
    </main>
</body>
</html>
