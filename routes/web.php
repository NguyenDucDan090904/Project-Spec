<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\CampaignController; // Đừng quên import cái này
use Illuminate\Http\Request;
use App\Models\Subscriber;

Route::get('/', function () {
    return view('welcome');
});

// Middleware điều hướng sau login
Route::get('/dashboard', function () {
    if (auth()->user()->role === 'admin') {
        return redirect()->route('admin.dashboard');
    }
    return redirect()->route('user.notifications');
})->middleware(['auth'])->name('dashboard');

// Group for USER
Route::middleware(['auth', 'role:user'])->prefix('user')->name('user.')->group(function () {
    Route::get('/notifications', function () {
        return view('dashboard'); // Hoặc view thông báo của bạn
    })->name('notifications');
});

// Group for ADMIN
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {

    // Trang chủ admin
    Route::get('/dashboard', function () {
        return view('dashboard'); // Đảm bảo trả về VIEW chứ không phải mỗi chuỗi string
    })->name('dashboard');

    // Route cho Campaign - Chuyển sang dùng Controller để xử lý view create.blade.php
    Route::get('/campaigns/create', [CampaignController::class, 'create'])->name('campaigns.create');
    Route::post('/campaigns/store', [CampaignController::class, 'store'])->name('campaigns.store');

    // Route Search Subscriber - Đã bỏ prefix /admin thừa
    Route::get('/subscribers/search', function (Request $request) {
        $search = $request->get('q');
        return Subscriber::where('name', 'LIKE', "%$search%")
            ->orWhere('email', 'LIKE', "%$search%")
            ->get(['id', 'name', 'email']);
    })->name('subscribers.search');

});

require __DIR__.'/auth.php';
