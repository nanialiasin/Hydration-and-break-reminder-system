<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('athletes', function (Blueprint $table) {
            $table->boolean('sip_prompt_seen')->default(false);
        });
    }

    public function down()
    {
        Schema::table('athletes', function (Blueprint $table) {
            $table->dropColumn('sip_prompt_seen');
        });
    }
};
