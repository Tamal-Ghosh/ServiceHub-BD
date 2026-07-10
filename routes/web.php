<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Customer\CustomerDashboardController;
use App\Http\Controllers\Provider\ProviderDashboardController;
use App\Http\Controllers\Provider\ProviderProfileController;
use App\Http\Controllers\Admin\AdminDashboardController;

// Landing page
Route::get('/', [HomeController::class, 'index'])->name('home');

// Guest routes (register & login)
Route::middleware('guest')->group(function () {
    Route::get('/register', [AuthController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

// Logout
Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// Public provider profile
Route::get('/provider/{id}/profile', [ProviderProfileController::class, 'show'])->name('provider.profile.show');

// Customer routes
Route::middleware(['auth', 'role:customer'])->prefix('customer')->name('customer.')->group(function () {
    Route::get('/dashboard', [CustomerDashboardController::class, 'index'])->name('dashboard');
    
    // Bookings
    Route::get('/bookings/create/{provider}', [\App\Http\Controllers\Customer\BookingController::class, 'create'])->name('bookings.create');
    Route::post('/bookings', [\App\Http\Controllers\Customer\BookingController::class, 'store'])->name('bookings.store');
    Route::get('/bookings', [\App\Http\Controllers\Customer\BookingController::class, 'index'])->name('bookings.index');
    Route::post('/bookings/{booking}/cancel', [\App\Http\Controllers\Customer\BookingController::class, 'cancel'])->name('bookings.cancel');
    Route::post('/bookings/{booking}/review', [\App\Http\Controllers\Customer\ReviewController::class, 'store'])->name('bookings.review');

    // Profile
    Route::get('/profile', [\App\Http\Controllers\UserProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [\App\Http\Controllers\UserProfileController::class, 'update'])->name('profile.update');
});

// Payment checkout routes (outside prefix to preserve standard names)
Route::middleware(['auth', 'role:customer'])->group(function () {
    Route::get('/bookings/{booking}/payment', [\App\Http\Controllers\PaymentController::class, 'show'])->name('payment.show');
    Route::post('/bookings/{booking}/payment/process', [\App\Http\Controllers\PaymentController::class, 'process'])->name('payment.process');
});

// Provider routes
Route::middleware(['auth', 'role:provider'])->prefix('provider')->name('provider.')->group(function () {
    Route::get('/dashboard', [ProviderDashboardController::class, 'index'])->name('dashboard');
    Route::get('/pending', function () {
        return view('provider.pending');
    })->name('pending');

    // Profile management
    Route::get('/profile/edit', [ProviderProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProviderProfileController::class, 'update'])->name('profile.update');
    Route::post('/profile/photo', [ProviderProfileController::class, 'uploadPhoto'])->name('profile.photo');
    Route::delete('/profile/photo', [ProviderProfileController::class, 'deletePhoto'])->name('profile.photo.delete');
    Route::put('/profile/availability', [ProviderProfileController::class, 'updateAvailability'])->name('profile.availability');

    // Bookings management
    Route::get('/bookings', [\App\Http\Controllers\Provider\BookingController::class, 'index'])->name('bookings.index');
    Route::post('/bookings/{booking}/status', [\App\Http\Controllers\Provider\BookingController::class, 'updateStatus'])->name('bookings.status');

    // Withdrawals
    Route::post('/withdrawals', [\App\Http\Controllers\Provider\WithdrawalController::class, 'store'])->name('withdrawals.store');

    // Review replies
    Route::post('/reviews/{review}/reply', [\App\Http\Controllers\Provider\ReviewController::class, 'reply'])->name('reviews.reply');
});

// Admin routes
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
    Route::post('/providers/{user}/approve', [AdminDashboardController::class, 'approve'])->name('providers.approve');
    
    // Withdrawals management
    Route::post('/withdrawals/{withdrawal}/status', [AdminDashboardController::class, 'updateWithdrawalStatus'])->name('withdrawals.status');

    // Profile
    Route::get('/profile', [\App\Http\Controllers\UserProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [\App\Http\Controllers\UserProfileController::class, 'update'])->name('profile.update');
});
