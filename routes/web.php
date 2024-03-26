<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

Auth::routes();

Route::get('/', [App\Http\Controllers\FeedController::class, 'index']);

Route::get('/feed', [App\Http\Controllers\FeedController::class, 'index'])->name('feed.index');
Route::get('/feed/{sheet}', [App\Http\Controllers\FeedController::class, 'show'])->name('feed.show');

Route::get('/sheets', [App\Http\Controllers\SheetController::class, 'index'])->name('sheet.index');
Route::get('/sheets/create', [App\Http\Controllers\SheetController::class, 'create'])->name('sheet.create');
Route::post('/sheets', [App\Http\Controllers\SheetController::class, 'store'])->name('sheet.store');
Route::get('/sheets/{sheet}/settings', [App\Http\Controllers\SheetController::class, 'settings'])->name('sheet.settings');
Route::patch('/sheets/{sheet}/settings', [App\Http\Controllers\SheetController::class, 'settingsUpdate'])->name('sheet.settings-update');
Route::get('/sheets/{sheet}', [App\Http\Controllers\SheetController::class, 'edit']);
Route::get('/sheets/{sheet}/edit', [App\Http\Controllers\SheetController::class, 'edit'])->name('sheet.edit');
Route::patch('/sheets/{sheet}', [App\Http\Controllers\SheetController::class, 'update'])->name('sheet.update');
Route::delete('/sheets/{sheet}', [App\Http\Controllers\SheetController::class, 'destroy'])->name('sheet.delete');