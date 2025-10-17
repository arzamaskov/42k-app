<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Админка') - 42k App</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100">
<div class="min-h-screen">
    <!-- Навигация -->
    <nav class="bg-white shadow">
        <div class="max-w-7xl mx-auto px-4">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <a href="{{ route('admin.dashboard') }}" class="text-xl font-bold text-gray-800">
                        42k Админка
                    </a>
                </div>
                <div class="flex items-center space-x-4">
                    <a href="{{ route('admin.users.index') }}" class="text-gray-600 hover:text-gray-900">
                        Пользователи
                    </a>
                    <a href="{{ route('admin.coaches.index') }}" class="text-gray-600 hover:text-gray-900">
                        Тренеры
                    </a>
                    <a href="{{ route('admin.analytics.index') }}" class="text-gray-600 hover:text-gray-900">
                        Аналитика
                    </a>
                    <span class="text-gray-600">{{ auth()->user()->name }}</span>
                </div>
            </div>
        </div>
    </nav>

    <!-- Контент -->
    <main class="max-w-7xl mx-auto py-6 px-4">
        @yield('content')
    </main>
</div>
</body>
</html>
