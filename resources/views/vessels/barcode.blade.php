<!DOCTYPE html>
<html lang="ar" dir="rtl">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>طباعة باركود {{ $vessel->name }}</title>
        <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;700&display=swap" rel="stylesheet">
        <style>
            body { font-family: 'Tajawal', sans-serif; background: #f8fafc; color: #0f172a; }
            .card { max-width: 420px; margin: 40px auto; background: #fff; border: 1px solid #e2e8f0; border-radius: 24px; padding: 32px; text-align: center; box-shadow: 0 20px 45px rgba(15,23,42,.08); }
            .print-btn { margin-top: 24px; display: inline-block; padding: 12px 20px; border-radius: 14px; background: #0ea5e9; color: white; text-decoration: none; }
            @media print { .print-btn { display: none; } body { background: #fff; } .card { box-shadow: none; border: none; margin: 0; } }
        </style>
    </head>
    <body>
        <div class="card">
            <div style="font-size: 22px; font-weight: 700; margin-bottom: 8px;">{{ $vessel->name }}</div>
            <div style="color: #64748b; margin-bottom: 20px;">{{ $vessel->vessel_number }}</div>
            <div>{!! QrCode::size(220)->generate($vessel->barcode) !!}</div>
            <div style="margin-top: 16px; font-family: monospace; word-break: break-all;">{{ $vessel->barcode }}</div>
            <a href="#" onclick="window.print(); return false;" class="print-btn">طباعة</a>
        </div>
    </body>
</html>
