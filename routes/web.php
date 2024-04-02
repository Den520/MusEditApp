<?php

use App\Http\Middleware\EnsureUserHasAccessToSheet;
use App\Http\Middleware\EnsureUserIsSheetOwner;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

Auth::routes();

Route::get('/', [App\Http\Controllers\FeedController::class, 'index']);

Route::get('/feed', [App\Http\Controllers\FeedController::class, 'index'])->name('feed.index');
Route::get('/feed/{sheet}', [App\Http\Controllers\FeedController::class, 'show'])->name('feed.show');

Route::get('/sheets', [App\Http\Controllers\SheetController::class, 'index'])->name('sheet.index');
Route::get('/sheets/create', [App\Http\Controllers\SheetController::class, 'create'])->name('sheet.create');
Route::post('/sheets', [App\Http\Controllers\SheetController::class, 'store'])->name('sheet.store');
Route::get('/sheets/{sheet}/edit', [App\Http\Controllers\SheetController::class, 'edit'])->name('sheet.edit')->middleware(EnsureUserHasAccessToSheet::class);
Route::patch('/sheets/{sheet}', [App\Http\Controllers\SheetController::class, 'update'])->name('sheet.update')->middleware(EnsureUserHasAccessToSheet::class);
Route::middleware([EnsureUserIsSheetOwner::class])->group(function () {
    Route::delete('/sheets/{sheet}', [App\Http\Controllers\SheetController::class, 'destroy'])->name('sheet.delete');
    Route::get('/sheets/{sheet}/settings', [App\Http\Controllers\SheetController::class, 'settings'])->name('sheet.settings');
    Route::patch('/sheets/{sheet}/settings', [App\Http\Controllers\SheetController::class, 'settingsUpdate'])->name('sheet.settings-update');
    Route::post('/sheets/{sheet}/settings/grant-access', [App\Http\Controllers\SheetController::class, 'grantAccess'])->name('sheet.grant-access');
    Route::post('/sheets/{sheet}/settings/revoke-access', [App\Http\Controllers\SheetController::class, 'revokeAccess'])->name('sheet.revoke-access');
});

Route::post('/export', [App\Http\Controllers\ExportController::class, 'export'])->name('export');