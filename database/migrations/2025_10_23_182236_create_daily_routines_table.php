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
        Schema::create('daily_routines', function (Blueprint $table) {
            $table->id();
            $table->time('time');
            $table->string('label')->nullable();
            $table->integer('user_id');
            $table->json('days')->nullable();
            $table->integer('remHour')->nullable();
            $table->integer('remMin')->nullable();
            $table->boolean('enabled')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('daily_routines');
    }
};
