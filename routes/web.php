<?php

use App\Http\Controllers\Dashboard\DashboardController;
use App\Http\Controllers\Frontend\FrontendController;
use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;

Route::get('/', [FrontendController::class, 'index'])->name('home');

Route::prefix('dashboard')->name('dashboard.')->group(function () {
    Route::middleware('auth')->group(function () {
        Route::get('/', [DashboardController::class, 'index'])->name('index');
    });
});

require __DIR__ . '/auth.php';
require __DIR__ . '/admin.php';
