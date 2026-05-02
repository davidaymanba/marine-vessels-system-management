<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Marine Vessels Management') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@300;400;500;700;800&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
            body { display: flex; align-items: center; justify-content: center; }
            display: flex;
            align-items: center;
            justify-content: center;
            width: 100%;
            min-height: 100vh;
            padding: 20px;
        }

        .auth-wrapper {
            width: 100%;
            max-width: 480px;
            animation: slideUp 0.8s ease-out;
        }

        .auth-header {
            text-align: center;
            margin-bottom: 40px;
            animation: fadeInDown 0.8s ease-out 0.1s both;
        }

        .auth-logo {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 80px;
            height: 80px;
            background: rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(10px);
            border-radius: 50%;
            margin-bottom: 20px;
            border: 2px solid rgba(255, 255, 255, 0.3);
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.15);
            animation: float 3s ease-in-out infinite;
        }

        .auth-logo svg {
            width: 45px;
            height: 45px;
            color: white;
        }

            .auth-title {
                color: white;
                font-size: 28px;
                font-weight: 700;
                margin-bottom: 10px;
                text-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            }

            .auth-subtitle {
                color: rgba(255, 255, 255, 0.9);
                font-size: 14px;
                font-weight: 400;
            }

            .auth-card {
                background: rgba(255, 255, 255, 0.95);
                backdrop-filter: blur(20px);
                border-radius: 20px;
                padding: 50px 40px;
                box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
                border: 1px solid rgba(255, 255, 255, 0.2);
                animation: slideUp 0.8s ease-out 0.3s both;
            }

            .form-group {
                margin-bottom: 25px;
            }

            .form-label {
                display: block;
                font-size: 14px;
                font-weight: 600;
                color: #2c3e50;
                margin-bottom: 10px;
                letter-spacing: 0.5px;
            }

            .form-input {
                width: 100%;
                padding: 14px 18px;
                font-size: 14px;
                border: 2px solid #e8e8e8;
                border-radius: 12px;
                transition: all 0.3s ease;
                font-family: 'Tajawal', sans-serif;
                background: #f9fafb;
            }

            .form-input:focus {
                outline: none;
                background: white;
                border-color: #667eea;
                box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.1);
                transform: translateY(-2px);
            }

            .password-toggle {
                position: absolute;
                left: 15px;
                top: 50%;
                transform: translateY(-50%);
                background: none;
                border: none;
                font-size: 18px;
                cursor: pointer;
                color: #999;
                transition: color 0.3s ease;
            }

            .password-toggle:hover {
                color: #667eea;
            }

            .password-field {
                position: relative;
            }

            .checkbox-group {
                display: flex;
                align-items: center;
                justify-content: space-between;
                margin-bottom: 30px;
                font-size: 14px;
            }

            .checkbox-wrapper {
                display: flex;
                align-items: center;
                gap: 8px;
            }

            .checkbox-input {
                width: 18px;
                height: 18px;
                cursor: pointer;
                accent-color: #667eea;
            }

            .checkbox-label {
                cursor: pointer;
                user-select: none;
                color: #4a5568;
                transition: color 0.3s ease;
            }

            .checkbox-label:hover {
                color: #667eea;
            }

            .forgot-password {
                color: #667eea;
                text-decoration: none;
                font-weight: 600;
                transition: color 0.3s ease;
            }

            .forgot-password:hover {
                color: #764ba2;
                text-decoration: underline;
            }

            .btn-submit {
                width: 100%;
                padding: 16px;
                background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                color: white;
                border: none;
                border-radius: 12px;
                font-size: 15px;
                font-weight: 600;
                cursor: pointer;
                transition: all 0.3s ease;
                font-family: 'Tajawal', sans-serif;
                letter-spacing: 0.5px;
                display: flex;
                align-items: center;
                justify-content: center;
                gap: 10px;
                box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
            }

            .btn-submit:hover {
                transform: translateY(-3px);
                box-shadow: 0 8px 25px rgba(102, 126, 234, 0.6);
            }

            .btn-submit:active {
                transform: translateY(-1px);
            }

            .error-alert {
                background: #fee;
                border: 1px solid #fcc;
                color: #c33;
                padding: 12px 15px;
                border-radius: 8px;
                margin-bottom: 20px;
                font-size: 13px;
                display: flex;
                gap: 8px;
                align-items: center;
                animation: slideIn 0.3s ease-out;
            }

            .divider {
                position: relative;
                margin: 30px 0;
                text-align: center;
                color: #999;
                font-size: 12px;
            }

            .divider::before {
                content: '';
                position: absolute;
                top: 50%;
                left: 0;
                right: 0;
                height: 1px;
                background: #ddd;
            }

            .divider span {
                position: relative;
                background: white;
                padding: 0 10px;
            }

            .demo-box {
                background: linear-gradient(135deg, #f5f7fa 0%, #e9ecef 100%);
                border-radius: 12px;
                padding: 20px;
                border: 1px solid #ddd;
                margin-top: 20px;
            }

            .demo-title {
                font-size: 12px;
                font-weight: 700;
                color: #2c3e50;
                margin-bottom: 12px;
                text-transform: uppercase;
                letter-spacing: 0.5px;
            }

            .demo-item {
                display: flex;
                justify-content: space-between;
                align-items: center;
                padding: 10px 12px;
                background: white;
                border-radius: 8px;
                margin-bottom: 8px;
                font-size: 13px;
                cursor: pointer;
                transition: all 0.3s ease;
            }

            .demo-item:last-child {
                margin-bottom: 0;
            }

            .demo-item:hover {
                background: #f0f4ff;
                transform: translateX(-3px);
            }

            .demo-code {
                font-family: 'Courier New', monospace;
                font-weight: 600;
                color: #2c3e50;
            }

            .copy-btn {
                color: #999;
                background: none;
                border: none;
                cursor: pointer;
                font-size: 14px;
                transition: color 0.3s ease;
            }

            .copy-btn:hover {
                color: #667eea;
            }

            .footer-link {
                text-align: center;
                margin-top: 25px;
                font-size: 14px;
                color: #666;
            }

            .footer-link a {
                color: #667eea;
                text-decoration: none;
                font-weight: 600;
                transition: color 0.3s ease;
            }

            .footer-link a:hover {
                color: #764ba2;
                text-decoration: underline;
            }

            .auth-footer {
                text-align: center;
                margin-top: 40px;
                color: rgba(255, 255, 255, 0.7);
                font-size: 12px;
            }

            @keyframes slideUp {
                from {
                    opacity: 0;
                    transform: translateY(30px);
                }
                to {
                    opacity: 1;
                    transform: translateY(0);
                }
            }

            @keyframes slideIn {
                from {
                    opacity: 0;
                    transform: translateX(20px);
                }
                to {
                    opacity: 1;
                    transform: translateX(0);
                }
            }

            @keyframes fadeInDown {
                from {
                    opacity: 0;
                    transform: translateY(-20px);
                }
                to {
                    opacity: 1;
                    transform: translateY(0);
                }
            }

            @keyframes float {
                0%, 100% { transform: translateY(0px); }
                50% { transform: translateY(-15px); }
            }

            @media (max-width: 768px) {
                .auth-card {
                    padding: 40px 25px;
                }

                .auth-title {
                    font-size: 24px;
                }

                .auth-subtitle {
                    font-size: 13px;
                }

                .form-input {
                    padding: 12px 15px;
                    font-size: 13px;
                }
            }
        </style>
    </head>
    <body>
        <div class="auth-container">
            <div class="auth-wrapper">
                <div class="auth-header">
                    <div class="auth-logo">
                        <svg fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 2L2 7v10c0 5.55 3.84 10.74 9 12 5.16-1.26 9-6.45 9-12V7l-10-5z"/>
                        </svg>
                    </div>
                    <h1 class="auth-title">Marine Vessels</h1>
                    <p class="auth-subtitle">نظام إدارة الوسائل البحرية</p>
                </div>

                <div class="auth-card">
                    {{ $slot }}
                </div>

                <div class="auth-footer">
                    <p>جميع الحقوق محفوظة © 2026</p>
                </div>
            </div>
        </div>
    </body>
</html>
