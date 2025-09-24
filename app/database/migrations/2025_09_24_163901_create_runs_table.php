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
        if (Schema::hasTable('runs')) {
            return;
        }

        Schema::create('runs', function (Blueprint $table) {
            $table->unsignedBigInteger('user_id');         // пока без FK, чтобы проще тестировать
            $table->timestampTz('run_at');                 // UTC по договору
            $table->unsignedInteger('distance');           // метры
            $table->unsignedInteger('duration');           // секунды
            $table->unsignedSmallInteger('avg_hr')->nullable();
            $table->unsignedSmallInteger('cadence')->nullable(); // шаг/мин
            $table->unsignedTinyInteger('rpe')->nullable();      // 1..10
            $table->unsignedBigInteger('shoe_id')->nullable();
            $table->text('notes')->nullable();
            $table->timestampsTz();

            $table->index(['user_id', 'run_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('runs');
    }
};
