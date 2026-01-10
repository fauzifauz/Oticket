<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\TicketController::class, 'index'])->name('dashboard');
    Route::post('/tickets/{id}/feedback', [\App\Http\Controllers\TicketController::class, 'feedback'])->name('tickets.feedback');
    Route::resource('tickets', \App\Http\Controllers\TicketController::class)->only(['create', 'store', 'show', 'update']);
    Route::get('/api/notifications/active', [\App\Http\Controllers\NotificationController::class, 'getActiveNotifications'])->name('notifications.active');


    Route::middleware('role:admin')->prefix('admin')->name('admin.')->group(function () {
        Route::redirect('/', '/admin/dashboard');
        Route::get('/dashboard', [\App\Http\Controllers\AdminController::class, 'index'])->name('dashboard');
        Route::get('/users/export', [\App\Http\Controllers\Admin\UserController::class, 'export'])->name('users.export');
        Route::get('/users/export-pdf', [\App\Http\Controllers\Admin\UserController::class, 'exportPdf'])->name('users.export-pdf');
        Route::get('/users/{user}/tickets', [\App\Http\Controllers\Admin\UserController::class, 'tickets'])->name('users.tickets');
        Route::post('/tickets/{ticket}/response', [\App\Http\Controllers\Admin\TicketController::class, 'storeResponse'])->name('tickets.store-response');
        Route::resource('users', \App\Http\Controllers\Admin\UserController::class);
        Route::get('/tickets/export', [\App\Http\Controllers\Admin\TicketController::class, 'export'])->name('tickets.export');
        Route::get('/tickets/export-pdf', [\App\Http\Controllers\Admin\TicketController::class, 'exportPdf'])->name('tickets.export-pdf');
        Route::get('/tickets/export-monthly', [\App\Http\Controllers\Admin\TicketController::class, 'exportMonthlyPdf'])->name('tickets.export-monthly-pdf');
        Route::get('/tickets/export-monthly-csv', [\App\Http\Controllers\Admin\TicketController::class, 'exportMonthlyCsv'])->name('tickets.export-monthly-csv');
        Route::get('/tickets/export-yearly', [\App\Http\Controllers\Admin\TicketController::class, 'exportYearlyPdf'])->name('tickets.export-yearly-pdf');
        Route::get('/tickets/export-yearly-csv', [\App\Http\Controllers\Admin\TicketController::class, 'exportYearlyCsv'])->name('tickets.export-yearly-csv');
        Route::resource('tickets', \App\Http\Controllers\Admin\TicketController::class)->only(['index', 'show', 'update', 'destroy']);
        Route::resource('slas', \App\Http\Controllers\Admin\SlaController::class);
        Route::resource('categories', \App\Http\Controllers\Admin\CategoryController::class);
        Route::resource('statuses', \App\Http\Controllers\Admin\TicketStatusController::class);
        Route::get('activity-logs', [\App\Http\Controllers\Admin\ActivityLogController::class, 'index'])->name('activity_logs.index');
        Route::post('activity-logs/clear', [\App\Http\Controllers\Admin\ActivityLogController::class, 'clearHistory'])->name('activity_logs.clear');
        Route::get('cms', [\App\Http\Controllers\Admin\CmsContentController::class, 'index'])->name('cms.index');
        Route::put('cms', [\App\Http\Controllers\Admin\CmsContentController::class, 'update'])->name('cms.update');
        Route::get('analytics', [\App\Http\Controllers\Admin\AnalyticsController::class, 'index'])->name('analytics.index');
        Route::get('media', [\App\Http\Controllers\Admin\MediaController::class, 'index'])->name('media.index');
        Route::delete('media', [\App\Http\Controllers\Admin\MediaController::class, 'destroy'])->name('media.destroy');
    });

    Route::middleware('role:support')->prefix('support')->name('support.')->group(function () {
        Route::get('/dashboard', [\App\Http\Controllers\SupportController::class, 'index'])->name('dashboard');
        Route::get('/tickets', [\App\Http\Controllers\SupportController::class, 'tickets'])->name('tickets.index');
        Route::get('/tickets/{id}', [\App\Http\Controllers\SupportController::class, 'show'])->name('tickets.show');
        Route::put('/tickets/{id}', [\App\Http\Controllers\SupportController::class, 'update'])->name('tickets.update');
    });

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::get('/waiting-approval', function () {
    return view('auth.waiting-approval');
})->name('waiting-approval');

Route::get('/employee/login', function () {
    return view('auth.employee-login');
})->middleware('guest')->name('employee.login');

require __DIR__.'/auth.php';
