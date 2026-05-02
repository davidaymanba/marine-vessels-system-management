<x-app-layout>
    <div class="space-y-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-slate-900">الإشعارات</h1>
                <p class="text-slate-500 mt-1">آخر التنبيهات الخاصة بالحركات والعمليات.</p>
            </div>
            <form method="POST" action="{{ route('notifications.read-all') }}">
                @csrf
                <button class="rounded-xl bg-slate-900 px-5 py-3 text-white">تعليم الكل كمقروء</button>
            </form>
        </div>

        <div class="rounded-2xl bg-white shadow-sm border border-slate-200 overflow-hidden">
            <div class="divide-y divide-slate-100">
                @forelse($notifications as $notification)
                    <div class="p-5 {{ $notification->read_at ? 'bg-white' : 'bg-sky-50' }}">
                        <div class="flex items-start justify-between gap-4">
                            <div>
                                <div class="font-semibold text-slate-900">{{ data_get($notification->data, 'label') }}</div>
                                <div class="mt-1 text-sm text-slate-600">
                                    {{ data_get($notification->data, 'vessel_name') }}
                                    @if(data_get($notification->data, 'exit_name'))
                                        - {{ data_get($notification->data, 'exit_name') }}
                                    @endif
                                </div>
                            </div>
                            <div class="text-xs text-slate-500">{{ optional($notification->created_at)->format('Y-m-d H:i') }}</div>
                        </div>
                    </div>
                @empty
                    <div class="p-8 text-center text-slate-500">لا توجد إشعارات.</div>
                @endforelse
            </div>
        </div>

        {{ $notifications->links() }}
    </div>
</x-app-layout>
