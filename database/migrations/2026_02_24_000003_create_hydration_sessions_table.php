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
        Schema::create('hydration_sessions', function (Blueprint $table) {
            $table->id();
            $table->string('sport')->nullable();
            $table->string('intensity')->nullable();
            $table->unsignedInteger('planned_duration_minutes')->default(0);
            $table->unsignedInteger('actual_duration_seconds')->default(0);
            $table->unsignedTinyInteger('temperature')->default(32);
            $table->unsignedTinyInteger('humidity')->default(74);
            $table->unsignedInteger('reminder_interval_minutes')->default(20);
            $table->unsignedInteger('alerts')->default(0);
            $table->unsignedInteger('followed')->default(0);
            $table->unsignedInteger('ignored')->default(0);
            $table->unsignedTinyInteger('hydration_score')->default(0);
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hydration_sessions');
    }
};
