<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (!Schema::hasColumn('athletes', 'created_by_coach')) {
            Schema::table('athletes', function (Blueprint $table) {
                $table->boolean('created_by_coach')->nullable()->default(null);
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('athletes', 'created_by_coach')) {
            Schema::table('athletes', function (Blueprint $table) {
                $table->dropColumn('created_by_coach');
            });
        }
    }
};
