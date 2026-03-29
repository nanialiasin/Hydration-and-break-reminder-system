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
        Schema::table('hydration_sessions', function (Blueprint $table) {
            if (!Schema::hasColumn('hydration_sessions', 'athlete_id')) {
                $table->string('athlete_id')->nullable()->after('id');
                $table->index('athlete_id');
            }

            if (!Schema::hasColumn('hydration_sessions', 'coach_id')) {
                $table->string('coach_id')->nullable()->after('athlete_id');
                $table->index('coach_id');
            }

            if (!Schema::hasColumn('hydration_sessions', 'assigned_by_coach')) {
                $table->boolean('assigned_by_coach')->default(false)->after('coach_id');
            }

            if (!Schema::hasColumn('hydration_sessions', 'started_at')) {
                $table->timestamp('started_at')->nullable()->after('completed_at');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('hydration_sessions', function (Blueprint $table) {
            if (Schema::hasColumn('hydration_sessions', 'started_at')) {
                $table->dropColumn('started_at');
            }

            if (Schema::hasColumn('hydration_sessions', 'assigned_by_coach')) {
                $table->dropColumn('assigned_by_coach');
            }

            if (Schema::hasColumn('hydration_sessions', 'coach_id')) {
                $table->dropIndex(['coach_id']);
                $table->dropColumn('coach_id');
            }

            if (Schema::hasColumn('hydration_sessions', 'athlete_id')) {
                $table->dropIndex(['athlete_id']);
                $table->dropColumn('athlete_id');
            }
        });
    }
};
