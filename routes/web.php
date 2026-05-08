<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\CampaignController;
use Illuminate\Http\Request;
use App\Models\Subscriber;

Route::get('/', function () {
    return view('auth.login');
});

// 1. Route after login
Route::get('/dashboard', function () {
    return auth()->user()->role === 'admin'
        ? redirect()->route('admin.campaigns.index')
        : redirect()->route('user.notifications');
})->middleware(['auth'])->name('dashboard');

// 2. Route for USER
Route::middleware(['auth', 'role:user'])->prefix('user')->name('user.')->group(function () {
    Route::get('/notifications', function () {
        return view('dashboard');
    })->name('notifications');
});

// 3. Route for ADMIN
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {

    // Route for Capaigns
    Route::resource('campaigns', CampaignController::class);

    // Search Subscriber with AJAX
    Route::get('/subscribers/search', function (Request $request) {
        $search = $request->get('q');
        return Subscriber::where('name', 'LIKE', "%$search%")
            ->orWhere('email', 'LIKE', "%$search%")
            ->get(['id', 'name', 'email']);
    })->name('subscribers.search');

    // Retry gửi email lỗi
    Route::post('/campaigns/{campaign}/retry-all', [CampaignController::class, 'retryAll'])
        ->name('campaigns.retry_all');

    Route::post('/campaigns/{campaign}/retry/{subscriber}', [CampaignController::class, 'retrySingle'])
        ->name('campaigns.retry_single');
});

require __DIR__.'/auth.php';
