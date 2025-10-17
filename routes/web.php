<?php

use App\Shared\UI\Http\Controllers\WebAuthController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Маршруты для аутентификации (русские)
Route::get('/вход', [WebAuthController::class, 'showLoginForm'])->name('login');
Route::post('/вход', [WebAuthController::class, 'login'])->name('login.post');
Route::post('/выход', [WebAuthController::class, 'logout'])->name('logout');
