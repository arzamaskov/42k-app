<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/runs', function () {
        return view('runs.index');
    })->name('runs.index');
    // Cyrillic-friendly aliases → 301 на текущие пути
    Route::get('/вход', fn () => redirect('/login', 301))->name('login.ru');
    Route::get('/пробежки', fn () => redirect('/runs', 301))->name('runs.index.ru');
});

require __DIR__ . '/auth.php';
