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
        Schema::create('athletes', function (Blueprint $table) {
            $table->id();
            $table->string('athlete_id')->unique();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('sport')->nullable();
            $table->string('status');
            $table->string('intensity')->nullable();
            $table->float('weight')->nullable();
            $table->float('height')->nullable();
            $table->float('bmi')->nullable();
            $table->float('alert_volume')->nullable(); // hydration alert volume
            $table->float('reminder_volume')->nullable(); // hydration reminder volume
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('athletes');
    }
};
