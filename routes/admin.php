<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;

Route::prefix('админка')->name('admin.')->middleware(['web', 'auth', 'admin'])->group(function () {
    Route::get('/', function () {
        return redirect()->route('admin.dashboard');
    });

    Route::get('/главная', function () {
        return view('admin.dashboard');
    })->name('dashboard');

    Route::get('/пользователи', function () {
        return view('admin.users.index');
    })->name('users.index');

    Route::get('/тренеры', function () {
        return view('admin.coaches.index');
    })->name('coaches.index');

    Route::get('/настройки', function () {
        return view('admin.settings.index');
    })->name('settings.index');

    Route::get('/аналитика', function () {
        return view('admin.analytics.index');
    })->name('analytics.index');
});
