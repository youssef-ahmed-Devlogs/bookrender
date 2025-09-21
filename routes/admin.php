<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\NewsletterController;
use App\Http\Controllers\Admin\PlanController;
use App\Http\Controllers\Admin\RatingController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\UserController;
use Illuminate\Support\Facades\Route;

Route::prefix('admin')->name('admin.')->middleware(['auth', 'admin-authorize'])->group(function () {
    Route::get('/', [AdminController::class, 'index'])->name('index');
    Route::resource('users', UserController::class);
    Route::resource('ratings', RatingController::class);

    Route::put('plans/toggle-status', [PlanController::class, 'toggleStatus'])->name('plans.toggleStatus');
    Route::resource('plans', PlanController::class);

    Route::get('newsletters', [NewsletterController::class, 'index'])->name('newsletters.index');
    Route::get('newsletters/export', [NewsletterController::class, 'export'])->name('newsletters.export');

    Route::prefix('settings')->name('settings.')->group(function () {
        Route::get('logo-site', [SettingController::class, 'logoSite'])->name('logo-site');
        route::put('upload-logos', [SettingController::class, 'uploadLogos'])->name('upload-logos');

        Route::get('font-colors', [SettingController::class, 'fontColors'])->name('font-colors');
        Route::put('font-colors', [SettingController::class, 'updateFontColors']);

        Route::get('features', [SettingController::class, 'features'])->name('features');
        Route::put('features', [SettingController::class, 'updateFeatures']);

        Route::get('information', [SettingController::class, 'information'])->name('information');
        Route::put('information', [SettingController::class, 'updateInformation']);
    });
});
