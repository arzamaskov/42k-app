<?php

use App\Http\Controllers\ProfileController;
use App\Runs\Presentation\Http\Controllers\RunsController;
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

    Route::prefix('пробежки')->name('runs.')->group(function () {
        Route::get('/', [RunsController::class, 'index'])->name('index');
        Route::get('/создать', [RunsController::class, 'create'])->name('create');
        Route::post('/', [RunsController::class, 'store'])->name('store');
        Route::get('/{run}/редактировать', [RunsController::class, 'edit'])->name('edit');
        Route::put('/{run}', [RunsController::class, 'update'])->name('update');
        Route::delete('/{run}', [RunsController::class, 'destroy'])->name('destroy');
    });
    Route::get('/runs', fn () => redirect('/пробежки', 301))->name('runs.index.redirect');

    // Cyrillic-friendly aliases → 301 на текущие пути
    Route::get('/вход', fn () => redirect('/login', 301))->name('login.ru');

    Route::prefix('runs')->name('runs.en.')->group(function () {
        Route::get('/', [RunsController::class, 'index'])->name('index');
        Route::get('/create', [RunsController::class, 'create'])->name('create');
        Route::post('/', [RunsController::class, 'store'])->name('store');
        Route::get('/{run}/edit', [RunsController::class, 'edit'])->name('edit');
        Route::put('/{run}', [RunsController::class, 'update'])->name('update');
        Route::delete('/{run}', [RunsController::class, 'destroy'])->name('destroy');
    });
});

require __DIR__ . '/auth.php';
