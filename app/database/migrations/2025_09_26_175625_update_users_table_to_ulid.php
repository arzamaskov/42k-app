<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Создаем новую таблицу с ULID
        Schema::create('users_new', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->timestamps();
        });

        // 2. Копируем данные из старой таблицы в новую
        $users = \DB::table('users')->get();
        foreach ($users as $user) {
            \DB::table('users_new')->insert([
                'id' => \Illuminate\Support\Str::ulid(),
                'name' => $user->name,
                'email' => $user->email,
                'email_verified_at' => $user->email_verified_at,
                'password' => $user->password,
                'remember_token' => $user->remember_token,
                'created_at' => $user->created_at,
                'updated_at' => $user->updated_at,
            ]);
        }

        // 3. Удаляем старую таблицу и переименовываем новую
        Schema::drop('users');
        Schema::rename('users_new', 'users');
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        /// Возвращаем старую структуру users
        Schema::drop('users');
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->timestamps();
        });
    }
};
