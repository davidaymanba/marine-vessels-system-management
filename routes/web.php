<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AuditLogController;
use App\Http\Controllers\ExitController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\MovementController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\VesselController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('dashboard');
});

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('dashboard')
        ->middleware('role:admin,operator');

    Route::get('/live-dashboard', [\App\Http\Controllers\LiveDashboardController::class, 'index'])
        ->name('live-dashboard')
        ->middleware('role:admin,operator');

    Route::get('/api/live-dashboard', [\App\Http\Controllers\LiveDashboardController::class, 'getLiveData'])
        ->name('live-dashboard.api')
        ->middleware('role:admin,operator');

    Route::get('/api/live-movements', [\App\Http\Controllers\LiveDashboardController::class, 'getMovementUpdates'])
        ->name('live-dashboard.movements')
        ->middleware('role:admin,operator');

    Route::get('/vessels', [VesselController::class, 'index'])
        ->name('vessels.index')
        ->middleware('role:admin,operator');

    Route::get('/vessels/create', [VesselController::class, 'create'])
        ->name('vessels.create')
        ->middleware('role:admin,operator');

    Route::post('/vessels', [VesselController::class, 'store'])
        ->name('vessels.store')
        ->middleware('role:admin,operator');

    Route::get('/movements/checkout', [MovementController::class, 'checkoutForm'])
        ->name('movements.checkout')
        ->middleware('role:admin,operator');

    Route::post('/movements/checkout', [MovementController::class, 'checkout'])
        ->name('movements.checkout.store')
        ->middleware('role:admin,operator');

    Route::get('/movements/scan', [MovementController::class, 'scanPage'])
        ->name('movements.scan')
        ->middleware('role:admin,operator');

    Route::get('/notifications', [NotificationController::class, 'index'])
        ->name('notifications.index');

    Route::get('/notifications/unread-count', [NotificationController::class, 'unreadCount'])
        ->name('notifications.unread-count');

    Route::post('/notifications/read-all', [NotificationController::class, 'markAllAsRead'])
        ->name('notifications.read-all');

    Route::post('/movements/checkin', [MovementController::class, 'checkinByScan'])
        ->name('movements.checkin')
        ->middleware('role:admin,operator');

    Route::middleware('role:admin')->group(function () {
        Route::get('/vessels/{vessel}/barcode', [VesselController::class, 'printBarcode'])
            ->name('vessels.barcode');

        Route::get('/vessels/{vessel}', [VesselController::class, 'show'])
            ->name('vessels.show');

        Route::get('/vessels/{vessel}/edit', [VesselController::class, 'edit'])
            ->name('vessels.edit');

        Route::put('/vessels/{vessel}', [VesselController::class, 'update'])
            ->name('vessels.update');

        Route::delete('/vessels/{vessel}', [VesselController::class, 'destroy'])
            ->name('vessels.destroy');

        Route::get('/movements', [MovementController::class, 'index'])
            ->name('movements.index');

        Route::get('/audit-logs', [AuditLogController::class, 'index'])
            ->name('audit-logs.index');

        Route::resource('exits', ExitController::class);

        Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
        Route::get('/reports/daily', [ReportController::class, 'daily'])->name('reports.daily');
        Route::get('/reports/weekly', [ReportController::class, 'weekly'])->name('reports.weekly');
        Route::get('/reports/monthly', [ReportController::class, 'monthly'])->name('reports.monthly');
        Route::get('/reports/custom', [ReportController::class, 'custom'])->name('reports.custom');
        Route::get('/reports/analytics', [ReportController::class, 'analytics'])->name('reports.analytics');
        Route::get('/reports/pdf', [ReportController::class, 'downloadPdf'])->name('reports.pdf');
        Route::get('/reports/excel', [ReportController::class, 'downloadExcel'])->name('reports.excel');
        Route::get('/reports/vessel/{id}', [ReportController::class, 'byVessel'])->name('reports.vessel');
        Route::get('/reports/exit/{id}', [ReportController::class, 'byExit'])->name('reports.exit');

        Route::resource('users', UserController::class);
    });
});

require __DIR__.'/auth.php';
