<!DOCTYPE html>
<html lang="ar" dir="rtl">
    <head>
        <meta charset="utf-8">
        <style>
            body {
                font-family: DejaVu Sans, sans-serif;
                direction: rtl;
                color: #0f172a;
                font-size: 12px;
            }
            .header {
                display: flex;
                justify-content: space-between;
                align-items: center;
                margin-bottom: 20px;
                border-bottom: 2px solid #0ea5e9;
                padding-bottom: 12px;
            }
            h1 {
                font-size: 20px;
                margin: 0;
            }
            .meta {
                color: #64748b;
                margin-top: 4px;
            }
            .stats {
                display: flex;
                gap: 12px;
                margin: 16px 0 22px;
            }
            .stat {
                flex: 1;
                border: 1px solid #e2e8f0;
                border-radius: 12px;
                padding: 12px;
                background: #f8fafc;
            }
            .stat .label {
                color: #64748b;
                font-size: 11px;
            }
            .stat .value {
                font-size: 20px;
                font-weight: bold;
                margin-top: 4px;
            }
            table {
                width: 100%;
                border-collapse: collapse;
            }
            th, td {
                border-bottom: 1px solid #e2e8f0;
                padding: 8px 6px;
                text-align: right;
            }
            th {
                background: #f8fafc;
                color: #64748b;
            }
        </style>
    </head>
    <body>
        <div class="header">
            <div>
                <h1>{{ $reportTitle }}</h1>
                <div class="meta">{{ $rangeLabel }}</div>
            </div>
            <div class="meta">Marine Vessels Management System</div>
        </div>

        <div class="stats">
            <div class="stat">
                <div class="label">الإجمالي</div>
                <div class="value">{{ $totals['total'] }}</div>
            </div>
            <div class="stat">
                <div class="label">الخروج</div>
                <div class="value">{{ $totals['exit'] }}</div>
            </div>
            <div class="stat">
                <div class="label">الدخول</div>
                <div class="value">{{ $totals['entry'] }}</div>
            </div>
        </div>

        <table>
            <thead>
                <tr>
                    <th>الوسيلة</th>
                    <th>النوع</th>
                    <th>المخرج</th>
                    <th>المستخدم</th>
                    <th>الوقت</th>
                </tr>
            </thead>
            <tbody>
                @forelse($movements as $movement)
                    <tr>
                        <td>{{ $movement->vessel?->name }}</td>
                        <td>{{ $movement->type === 'exit' ? 'خروج' : 'دخول' }}</td>
                        <td>{{ $movement->exit?->name ?? '-' }}</td>
                        <td>{{ $movement->user?->name }}</td>
                        <td>{{ optional($movement->moved_at)->format('Y-m-d H:i:s') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5">لا توجد نتائج.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </body>
</html>
