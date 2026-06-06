<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\MenuController as AdminMenuController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Admin\MenuImportController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Customer\MenuController as CustomerMenuController;
use App\Http\Controllers\Customer\OrderController as CustomerOrderController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\Rider\DashboardController as RiderDashboardController;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::middleware('guest')->group(function () {
    Route::get('/login', function () {
        return view('auth.login');
    })->name('login');

    Route::post('/login', [AuthenticatedSessionController::class, 'store']);

    Route::get('/register', function () {
        return view('auth.register');
    })->name('register');

    Route::post('/register', [RegisteredUserController::class, 'store']);
});

Route::post('/logout', function () {
    Auth::logout();

    request()->session()->invalidate();
    request()->session()->regenerateToken();

    return redirect()
        ->route('home')
        ->with('success', 'Logged out successfully.');
})->middleware('auth')->name('logout');

Route::get('/dashboard', function () {
    if (Auth::user()?->role === 'admin') {
        return redirect()->route('admin.dashboard');
    }

    if (Auth::user()?->role === 'rider') {
        return redirect()->route('rider.dashboard');
    }

    return redirect()->route('customer.menu');
})->middleware('auth')->name('dashboard');

Route::get('/menu', [CustomerMenuController::class, 'index'])
    ->name('menu.public');

Route::prefix('customer')
    ->middleware('auth')
    ->name('customer.')
    ->group(function () {
        Route::get('/dashboard', function () {
            return redirect()->route('customer.menu');
        })->name('dashboard');

        Route::get('/menu', [CustomerMenuController::class, 'index'])
            ->name('menu');

        Route::get('/orders', [CustomerOrderController::class, 'index'])
            ->name('orders.index');

        Route::post('/orders', [CustomerOrderController::class, 'store'])
            ->name('orders.store');

        Route::get('/orders/{order}', [CustomerOrderController::class, 'show'])
            ->name('orders.show');

        Route::patch('/orders/{order}/cancel', [CustomerOrderController::class, 'cancel'])
            ->name('orders.cancel');
    });


Route::prefix('rider')
    ->middleware(['auth', 'rider'])
    ->name('rider.')
    ->group(function () {
        Route::get('/dashboard', [RiderDashboardController::class, 'index'])
            ->name('dashboard');

        Route::get('/orders/{order}', [RiderDashboardController::class, 'show'])
            ->name('orders.show');

        Route::patch('/orders/{order}/out-for-delivery', [RiderDashboardController::class, 'markOutForDelivery'])
            ->name('orders.out_for_delivery');

        Route::patch('/orders/{order}/delivered', [RiderDashboardController::class, 'markDelivered'])
            ->name('orders.delivered');
    });

Route::prefix('admin')
    ->middleware(['auth', 'admin'])
    ->name('admin.')
    ->group(function () {
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])
            ->name('dashboard');

        Route::resource('menu', AdminMenuController::class)
            ->parameters(['menu' => 'menuItem'])
            ->names('menu');

        Route::get('/orders', [AdminOrderController::class, 'index'])
            ->name('orders.index');

        Route::get('/orders/{order}', [AdminOrderController::class, 'show'])
            ->name('orders.show');

        Route::patch('/orders/{order}/status', [AdminOrderController::class, 'updateStatus'])
            ->name('orders.status');

        Route::patch('/orders/{order}/approve', [AdminOrderController::class, 'approve'])
            ->name('orders.approve');

        Route::patch('/orders/{order}/reject', [AdminOrderController::class, 'reject'])
            ->name('orders.reject');

        Route::get('/reports', [ReportController::class, 'ordersReport'])
            ->name('reports.index');

        Route::get('/reports/pdf', [ReportController::class, 'exportPdf'])
            ->name('reports.pdf');

        Route::get('/reports/csv', [ReportController::class, 'exportCsv'])
            ->name('reports.csv');

        Route::get('/reports/import-csv', [MenuImportController::class, 'create'])
            ->name('reports.import.create');

        Route::get('/reports/import-csv/sample', [MenuImportController::class, 'sampleCsv'])
            ->name('reports.import.sample');

        Route::post('/reports/import-csv', [MenuImportController::class, 'store'])
            ->name('reports.import.store');
    });