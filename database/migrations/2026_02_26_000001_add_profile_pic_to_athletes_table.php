<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        if (!Schema::hasColumn('athletes', 'profile_pic')) {
            Schema::table('athletes', function (Blueprint $table) {
                $table->string('profile_pic')->nullable()->after('status');
            });
        }
    }

    public function down()
    {
        if (Schema::hasColumn('athletes', 'profile_pic')) {
            Schema::table('athletes', function (Blueprint $table) {
                $table->dropColumn('profile_pic');
            });
        }
    }
};