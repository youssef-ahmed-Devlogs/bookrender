<?php

use App\Http\Controllers\Dashboard\BookController;
use App\Http\Controllers\Dashboard\DashboardController;
use App\Http\Controllers\Frontend\FrontendController;
use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;

Route::get('/', [FrontendController::class, 'index'])->name('home');

Route::prefix('dashboard')->name('dashboard.')->group(function () {
    Route::middleware('auth')->group(function () {
        Route::get('/', [DashboardController::class, 'index'])->name('index');
        Route::get('profile', [DashboardController::class, 'profile'])->name('profile');
        Route::get('help-center', [DashboardController::class, 'helpCenter'])->name('help-center');

        Route::resource('books', BookController::class);
    });
});

Route::get('about-us', [FrontendController::class, 'aboutUs'])->name('about-us');
Route::get('pricing-plans', [FrontendController::class, 'pricingPlans'])->name('pricing-plans');
Route::get('affiliate-program', [FrontendController::class, 'affiliateProgram'])->name('affiliate-program');
Route::get('refund-policy', [FrontendController::class, 'refundPolicy'])->name('refund-policy');
Route::get('privacy-policy', [FrontendController::class, 'privacyPolicy'])->name('privacy-policy');
Route::get('terms-conditions', [FrontendController::class, 'termsConditions'])->name('terms-conditions');
Route::post('newsletters/subscribe', [FrontendController::class, 'subscribe'])->name('newsletters.subscribe');

require __DIR__ . '/auth.php';
require __DIR__ . '/admin.php';
