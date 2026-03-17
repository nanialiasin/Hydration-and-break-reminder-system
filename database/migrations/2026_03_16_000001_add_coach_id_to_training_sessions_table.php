<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('training_sessions', function (Blueprint $table) {
            $table->unsignedBigInteger('coach_id')->nullable()->after('sport');
            // If you want to enforce foreign key constraint, uncomment below:
            // $table->foreign('coach_id')->references('id')->on('coaches')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('training_sessions', function (Blueprint $table) {
            $table->dropColumn('coach_id');
        });
    }
};
