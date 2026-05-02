<!DOCTYPE html>
<html lang="ar" dir="rtl">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Marine Vessels Management System') }}</title>

        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@300;400;500;700;800&display=swap" rel="stylesheet">

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="bg-slate-100 text-slate-900 font-[Tajawal]">
        @php
            $user = auth()->user();
            $unreadNotificationsCount = $user ? $user->unreadNotifications()->count() : 0;
        @endphp

        <div class="min-h-screen flex flex-col lg:flex-row">
            <aside class="w-full lg:w-72 bg-[#0F172A] text-slate-100 flex flex-col lg:min-h-screen">
                <div class="px-6 py-6 border-b border-white/10">
                    <div class="text-lg font-bold tracking-wide">Marine Vessels</div>
                    <div class="text-xs text-slate-300 mt-1">Management System</div>
                </div>

                <nav class="flex-1 px-4 py-6 space-y-2">
                    <a href="{{ route('dashboard') }}" class="block rounded-lg px-4 py-3 text-sm transition {{ request()->routeIs('dashboard') ? 'bg-[#0EA5E9] text-white' : 'text-slate-200 hover:bg-white/10' }}">لوحة التحكم</a>
                    <a href="{{ route('live-dashboard') }}" class="block rounded-lg px-4 py-3 text-sm transition {{ request()->routeIs('live-dashboard') ? 'bg-[#0EA5E9] text-white' : 'text-slate-200 hover:bg-white/10' }}">لوحة التحكم الحية</a>
                    <a href="{{ route('vessels.index') }}" class="block rounded-lg px-4 py-3 text-sm transition {{ request()->routeIs('vessels.index') ? 'bg-[#0EA5E9] text-white' : 'text-slate-200 hover:bg-white/10' }}">الوسائل البحرية</a>
                    <a href="{{ route('movements.checkout') }}" class="block rounded-lg px-4 py-3 text-sm transition {{ request()->routeIs('movements.checkout') ? 'bg-[#0EA5E9] text-white' : 'text-slate-200 hover:bg-white/10' }}">تسجيل خروج</a>
                    <a href="{{ route('movements.scan') }}" class="block rounded-lg px-4 py-3 text-sm transition {{ request()->routeIs('movements.scan') ? 'bg-[#0EA5E9] text-white' : 'text-slate-200 hover:bg-white/10' }}">مسح الباركود</a>

                    @if($user && $user->role === 'admin')
                        <a href="{{ route('movements.index') }}" class="block rounded-lg px-4 py-3 text-sm transition {{ request()->routeIs('movements.index') ? 'bg-[#0EA5E9] text-white' : 'text-slate-200 hover:bg-white/10' }}">سجل الحركات</a>
                        <a href="{{ route('reports.index') }}" class="block rounded-lg px-4 py-3 text-sm transition {{ request()->routeIs('reports.*') && !request()->routeIs('reports.analytics') ? 'bg-[#0EA5E9] text-white' : 'text-slate-200 hover:bg-white/10' }}">التقارير</a>
                        <a href="{{ route('reports.analytics') }}" class="block rounded-lg px-4 py-3 text-sm transition {{ request()->routeIs('reports.analytics') ? 'bg-[#0EA5E9] text-white' : 'text-slate-200 hover:bg-white/10' }}">التحليلات المتقدمة</a>
                        <a href="{{ route('audit-logs.index') }}" class="block rounded-lg px-4 py-3 text-sm transition {{ request()->routeIs('audit-logs.*') ? 'bg-[#0EA5E9] text-white' : 'text-slate-200 hover:bg-white/10' }}">سجل التدقيق</a>
                        <a href="{{ route('exits.index') }}" class="block rounded-lg px-4 py-3 text-sm transition {{ request()->routeIs('exits.*') ? 'bg-[#0EA5E9] text-white' : 'text-slate-200 hover:bg-white/10' }}">المخارج</a>
                        <a href="{{ route('users.index') }}" class="block rounded-lg px-4 py-3 text-sm transition {{ request()->routeIs('users.*') ? 'bg-[#0EA5E9] text-white' : 'text-slate-200 hover:bg-white/10' }}">المستخدمون</a>
                    @endif

                    <a href="{{ route('notifications.index') }}" class="flex items-center justify-between rounded-lg px-4 py-3 text-sm transition {{ request()->routeIs('notifications.*') ? 'bg-[#0EA5E9] text-white' : 'text-slate-200 hover:bg-white/10' }}">
                        <span>الإشعارات</span>
                        <span x-data="notificationBadge({{ $unreadNotificationsCount }}, '{{ route('notifications.unread-count') }}')" x-init="init()" x-text="count" x-show="count > 0" class="rounded-full bg-rose-500 px-2 py-0.5 text-xs font-bold text-white"></span>
                    </a>
                </nav>

                <div class="px-6 py-5 border-t border-white/10 text-xs text-slate-400">
                    الإصدار 1.0
                </div>
            </aside>

            <div class="flex-1 flex flex-col min-w-0">
                <header class="bg-white border-b border-slate-200" x-data="notificationBadge({{ $unreadNotificationsCount }}, '{{ route('notifications.unread-count') }}')" x-init="init()">
                    <div class="flex items-center justify-between px-6 py-4 gap-4">
                        <div>
                            <div class="text-sm text-slate-500">مرحبا بك</div>
                            <div class="text-lg font-semibold">{{ $user?->name }}</div>
                        </div>

                        <div class="flex items-center gap-3">
                            <a href="{{ route('notifications.index') }}" class="relative inline-flex h-11 w-11 items-center justify-center rounded-xl border border-slate-200 bg-white text-slate-700 hover:bg-slate-50">
                                <span>🔔</span>
                                <span x-text="count" x-show="count > 0" class="absolute -top-2 -left-2 rounded-full bg-rose-500 px-2 py-0.5 text-[11px] font-bold text-white"></span>
                            </a>

                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="px-4 py-2 rounded-lg bg-[#0EA5E9] text-white text-sm hover:bg-sky-500">تسجيل خروج</button>
                            </form>
                        </div>
                    </div>
                </header>

                <main class="flex-1 p-4 sm:p-6 lg:p-8">
                    @if(session('success'))
                        <div class="mb-4 rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-emerald-700">{{ session('success') }}</div>
                    @endif

                    @if($errors->any())
                        <div class="mb-4 rounded-lg border border-rose-200 bg-rose-50 px-4 py-3 text-rose-700">
                            <ul class="list-disc list-inside space-y-1">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    {{ $slot }}
                </main>
            </div>
        </div>
    </body>
</html>
