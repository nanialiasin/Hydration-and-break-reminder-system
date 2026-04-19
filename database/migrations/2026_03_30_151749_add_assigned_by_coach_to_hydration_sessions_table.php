<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAssignedByCoachToHydrationSessionsTable extends Migration
{
    public function up()
    {
        Schema::table('hydration_sessions', function (Blueprint $table) {
            $table->boolean('assigned_by_coach')->default(false)->after('coach_id');
        });
    }

    public function down()
    {
        Schema::table('hydration_sessions', function (Blueprint $table) {
            $table->dropColumn('assigned_by_coach');
        });
    }
}