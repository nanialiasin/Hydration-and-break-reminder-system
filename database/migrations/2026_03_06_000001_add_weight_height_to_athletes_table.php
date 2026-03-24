<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('athletes', function (Blueprint $table) {
            if (!Schema::hasColumn('athletes', 'weight')) {
                $table->float('weight')->nullable();
            }

            if (!Schema::hasColumn('athletes', 'height')) {
                $table->float('height')->nullable();
            }
        });
    }

    public function down(): void
    {
        Schema::table('athletes', function (Blueprint $table) {
            if (Schema::hasColumn('athletes', 'weight')) {
                $table->dropColumn('weight');
            }

            if (Schema::hasColumn('athletes', 'height')) {
                $table->dropColumn('height');
            }
        });
    }
};
