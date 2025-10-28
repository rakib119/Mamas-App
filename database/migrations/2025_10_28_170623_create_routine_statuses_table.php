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
        Schema::create('routine_statuses', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('alarm_id');
            $table->unsignedBigInteger('user_id');
            $table->date('date'); // Alarm date (for daily record)
            $table->boolean('completed')->default(false);
            $table->timestamps();
            $table->foreign('alarm_id')->references('id')->on('daily_routines')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('routine_statuses');
    }
};
