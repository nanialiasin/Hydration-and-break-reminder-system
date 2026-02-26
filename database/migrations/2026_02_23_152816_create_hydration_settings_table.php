<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('hydration_settings', function (Blueprint $table) {
            $table->id();
            $table->string('intensity');
            $table->integer('hydration_reminder');
            $table->integer('break_duration');
            $table->integer('break_reminder');
            $table->timestamps();
        });

        DB::table('hydration_settings')->insert([
            [
                'intensity' => 'Beginner',
                'hydration_reminder' => 15,
                'break_duration' => 5,
                'break_reminder' => 10,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'intensity' => 'Intermediate',
                'hydration_reminder' => 10,
                'break_duration' => 5,
                'break_reminder' => 8,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'intensity' => 'Advanced',
                'hydration_reminder' => 5,
                'break_duration' => 5,
                'break_reminder' => 5,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hydration_settings');
    }
};
