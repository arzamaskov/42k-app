<?php

use App\Users\UI\Http\AuthController;
use Illuminate\Support\Facades\Route;

// Route::post('/register', [AuthController::class, 'register']);
// Route::post('/login', [AuthController::class, 'login']);
// Route::middleware('auth.api')->group(function () {
//    Route::post('/logout', [AuthController::class, 'logout']);
//    Route::get('/user', [AuthController::class, 'user']);
// });

// Тестовые маршруты для middleware
Route::middleware(['auth.api', 'role:admin'])->get('/admin/test', function () {
    return response()->json(['message' => 'Admin access granted']);
});

Route::middleware(['auth.api', 'role:coach'])->get('/coach/test', function () {
    return response()->json(['message' => 'Coach access granted']);
});

Route::middleware(['auth.api', 'role:admin,coach'])->get('/privileged/test', function () {
    return response()->json(['message' => 'Privileged access granted']);
});

// Публичные маршруты
Route::post('/регистрация', [AuthController::class, 'register']);
Route::post('/вход', [AuthController::class, 'login']);

// Защищенные маршруты
Route::middleware('auth.api')->group(function () {
    Route::post('/выход', [AuthController::class, 'logout']);
    Route::get('/профиль', [AuthController::class, 'user']);

    // Пробежки
    Route::prefix('пробежки')->name('runs.')->group(function () {
        Route::get('/',
            //            [RunController::class, 'index']
            function () {
                return response()->json(['message' => 'Runs endpoint']);
            }
        )->name('index');
        Route::post('/',
            //            [RunController::class, 'store']
            function () {
                return response()->json(['message' => 'Runs endpoint']);
            }
        )->name('store');
        Route::get('/{run}',
            //            [RunController::class, 'show']
            function () {
                return response()->json(['message' => 'Runs endpoint']);
            }
        )->name('show');
        Route::put('/{run}',
            //            [RunController::class, 'update']
            function () {
                return response()->json(['message' => 'Runs endpoint']);
            }
        )->name('update');
        Route::delete('/{run}',
            //            [RunController::class, 'destroy']
            function () {
                return response()->json(['message' => 'Runs endpoint']);
            }
        )->name('destroy');
    });

    // Планы тренировок
    Route::prefix('планы-тренировок')->name('training-plans.')->group(function () {
        Route::get('/',
            //            [TrainingPlanController::class, 'myPlans']
            function () {
                return response()->json(['message' => 'Training endpoint']);
            }
        )->name('index');
        Route::post('/',
            //            [TrainingPlanController::class, 'store']
            function () {
                return response()->json(['message' => 'Training endpoint']);
            }
        )->name('store');
        Route::get('/{plan}',
            //            [TrainingPlanController::class, 'show']
            function () {
                return response()->json(['message' => 'Training endpoint']);
            }
        )->name('show');
        Route::put('/{plan}',
            //            [TrainingPlanController::class, 'update']
            function () {
                return response()->json(['message' => 'Training endpoint']);
            }
        )->name('update');
        Route::delete('/{plan}',
            //            [TrainingPlanController::class, 'destroy']
            function () {
                return response()->json(['message' => 'Training endpoint']);
            }
        )->name('destroy');
    });

    // Маршруты для тренеров
    Route::middleware('role:coach,admin')->prefix('тренер')->name('coach.')->group(function () {
        Route::get('/подопечные',
            //            [CoachController::class, 'athletes']
            function () {
                return response()->json(['message' => 'Coach endpoint']);
            }
        )->name('athletes');
        Route::get('/подопечные/{user}/прогресс',
            //            [CoachController::class, 'athleteProgress']
            function () {
                return response()->json(['message' => 'Coach endpoint']);
            }
        )->name('athlete.progress');
        Route::post('/планы-тренировок/{plan}/назначить',
            //            [TrainingPlanController::class, 'assign']
            function () {
                return response()->json(['message' => 'Coach endpoint']);
            }
        )->name('assign.plan');
    });

    // Маршруты для админов
    Route::middleware('role:admin')->prefix('админ')->name('admin.')->group(function () {
        Route::get('/пользователи',
            //            [AdminUserController::class, 'index']
            function () {
                return response()->json(['message' => 'Admin endpoint']);
            }
        )->name('users.index');
        Route::post('/пользователи',
            //            [AdminUserController::class, 'store']
            function () {
                return response()->json(['message' => 'Admin endpoint']);
            }
        )->name('users.store');
        Route::get('/пользователи/{user}',
            //            [AdminUserController::class, 'show']
            function () {
                return response()->json(['message' => 'Admin endpoint']);
            }
        )->name('users.show');
        Route::put('/пользователи/{user}',
            //            [AdminUserController::class, 'update']
            function () {
                return response()->json(['message' => 'Admin endpoint']);
            }
        )->name('users.update');
        Route::delete('/пользователи/{user}',
            //            [AdminUserController::class, 'destroy']
            function () {
                return response()->json(['message' => 'Admin endpoint']);
            }
        )->name('users.destroy');
        Route::put('/пользователи/{user}/роль',
            //            [AdminUserController::class, 'changeRole']
            function () {
                return response()->json(['message' => 'Admin endpoint']);
            }
        )->name('users.change-role');
    });
});
