<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddStayLoggedInToCoachesTable extends Migration
{
    public function up()
    {
        Schema::table('coaches', function (Blueprint $table) {
            $table->boolean('stay_logged_in')->default(false);
        });
    }

    public function down()
    {
        Schema::table('coaches', function (Blueprint $table) {
            $table->dropColumn('stay_logged_in');
        });
    }
}
