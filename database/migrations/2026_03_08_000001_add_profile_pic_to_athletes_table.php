<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddProfilePicToAthletesTable extends Migration {
    public function up(): void
    {
        if (!Schema::hasColumn('athletes', 'profile_pic')) {
            Schema::table('athletes', function (Blueprint $table) {
                $table->string('profile_pic')->nullable();
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('athletes', 'profile_pic')) {
            Schema::table('athletes', function (Blueprint $table) {
                $table->dropColumn('profile_pic');
            });
        }
    }
}
