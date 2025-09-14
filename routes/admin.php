<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\NewsletterController;
use App\Http\Controllers\Admin\PlanController;
use App\Http\Controllers\Admin\UserController;
use Illuminate\Support\Facades\Route;

Route::prefix('admin')->name('admin.')->middleware(['auth', 'admin-authorize'])->group(function () {
    Route::get('/', [AdminController::class, 'index'])->name('index');
    Route::resource('users', UserController::class);

    Route::put('plans/toggle-status', [PlanController::class, 'toggleStatus'])->name('plans.toggleStatus');
    Route::resource('plans', PlanController::class);

    Route::get('newsletters', [NewsletterController::class, 'index'])->name('newsletters.index');
    Route::get('newsletters/export', [NewsletterController::class, 'export'])->name('newsletters.export');
});
