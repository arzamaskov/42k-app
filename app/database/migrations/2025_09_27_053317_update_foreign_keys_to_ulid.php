<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Обновляем sessions.user_id с bigint на string для ULID
        Schema::table('sessions', function (Blueprint $table) {
            // Проверяем и удаляем foreign key только если он существует
            if (Schema::hasColumn('sessions', 'user_id')) {
                $table->dropIndex(['user_id']);
            }
        });

        Schema::table('sessions', function (Blueprint $table) {
            $table->char('user_id', 26)->nullable()->change();
            $table->index('user_id');
        });

        // Обновляем runs.user_id с bigint на string для ULID
        Schema::table('runs', function (Blueprint $table) {
            $table->dropIndex(['user_id', 'run_at']);
        });

        Schema::table('runs', function (Blueprint $table) {
            $table->char('user_id', 26)->change();
            $table->index(['user_id', 'run_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Возвращаем sessions.user_id обратно к bigint
        Schema::table('sessions', function (Blueprint $table) {
            $table->dropIndex(['user_id']);
        });

        Schema::table('sessions', function (Blueprint $table) {
            $table->unsignedBigInteger('user_id')->nullable()->change();
            $table->index('user_id');
        });

        // Возвращаем runs.user_id обратно к bigint
        Schema::table('runs', function (Blueprint $table) {
            $table->dropIndex(['user_id', 'run_at']);
        });

        Schema::table('runs', function (Blueprint $table) {
            $table->unsignedBigInteger('user_id')->change();
            $table->index(['user_id', 'run_at']);
        });
    }
};
