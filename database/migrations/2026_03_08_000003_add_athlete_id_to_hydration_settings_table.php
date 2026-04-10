<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAthleteIdToHydrationSettingsTable extends Migration
{
    public function up()
    {
        Schema::table('hydration_settings', function (Blueprint $table) {
            $table->string('athlete_id')->nullable()->after('id');
        });
    }

    public function down()
    {
        Schema::table('hydration_settings', function (Blueprint $table) {
            $table->dropColumn('athlete_id');
        });
    }
}
