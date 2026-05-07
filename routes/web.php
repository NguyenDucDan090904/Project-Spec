<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\CampaignController;
use Illuminate\Http\Request;
use App\Models\Subscriber;

Route::get('/', function () {
    return view('welcome');
});

// 1. Điều hướng sau Login (Dashboard Switcher)
Route::get('/dashboard', function () {
    return auth()->user()->role === 'admin'
        ? redirect()->route('admin.dashboard')
        : redirect()->route('user.notifications');
})->middleware(['auth'])->name('dashboard');

// 2. Khu vực dành cho USER
Route::middleware(['auth', 'role:user'])->prefix('user')->name('user.')->group(function () {
    Route::get('/notifications', function () {
        return view('dashboard');
    })->name('notifications');
});

// 3. Khu vực dành cho ADMIN
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {

    // Trang chủ quản trị
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    // Quản lý Chiến dịch (Sử dụng Resource Controller)
    // Sẽ tự động tạo các route: admin.campaigns.index, create, store, show...
    Route::resource('campaigns', CampaignController::class);

    // Tìm kiếm Subscriber
    Route::get('/subscribers/search', function (Request $request) {
        $search = $request->get('q');
        return Subscriber::where('name', 'LIKE', "%$search%")
            ->orWhere('email', 'LIKE', "%$search%")
            ->get(['id', 'name', 'email']);
    })->name('subscribers.search');
});

require __DIR__.'/auth.php';
