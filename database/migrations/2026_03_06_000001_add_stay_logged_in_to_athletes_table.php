<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddStayLoggedInToAthletesTable extends Migration
{
    public function up()
    {
        Schema::table('athletes', function (Blueprint $table) {
            $table->boolean('stay_logged_in')->default(false)->after('profile_pic');
        });
    }

    public function down()
    {
        Schema::table('athletes', function (Blueprint $table) {
            $table->dropColumn('stay_logged_in');
        });
    }
}
