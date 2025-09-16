<?php

use App\Http\Controllers\Dashboard\BookController;
use App\Http\Controllers\Dashboard\ChapterController;
use App\Models\Setting;
use Illuminate\Support\Facades\Route;

// Books & Chapters
Route::post('book/fetch/update-title', [BookController::class, 'updateTitle'])->name('book.fetch.update-title');
Route::resource('book', BookController::class);
Route::get('chapter/create/{id}', [ChapterController::class, 'create'])->name('chapter.create.custom');
Route::put('chapter/updateChapter/{chapter}', [ChapterController::class, 'updateChapter'])->name('chapter.updateChapter');
Route::resource('chapter', ChapterController::class);

// AI-related chapter actions
Route::post('update-chapter-ai/{id}', [ChapterController::class, 'updateContentAI'])->name('chapter.update.ai');
Route::post('generate-chapter-ai', [ChapterController::class, 'generateContentAI'])->name('generateContentAI');

// Book export
Route::get('export-book/{bookId}', [BookController::class, 'exportBook'])->name('export.book');

// Prediction prompt UI & helpers
Route::get('predict', function () {

    if (!session()->has('generated_content')) {
        return redirect()->route('dashboard.index');
    }

    $settings = Setting::first();
    return view('dashboard.chapters.predict', ['settings' => $settings]);
})->name('showpredict');

Route::post('clear-predict-session', [ChapterController::class, 'clearSession'])->name('clear.predict.session');
