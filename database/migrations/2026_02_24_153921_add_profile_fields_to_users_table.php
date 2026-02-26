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
        Schema::table('users', function (Blueprint $table) {
            $table->float('weight')->nullable();
            $table->float('height')->nullable();
            $table->string('sport')->nullable();
            $table->string('intensity')->nullable();
            $table->boolean('stay_logged_in')->default(false);
            $table->integer('alert_volume')->default(50);
            $table->integer('reminder_volume')->default(50);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            //
        });
    }
};
