<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('rtslieues', function (Blueprint $table) {
            $table->dropColumn("votant");
            $table->dropColumn("bulnull");
            $table->dropColumn("hs");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('rtslieues', function (Blueprint $table) {
            $table->integer("votant");
            $table->integer("bulnull");
            $table->integer("hs");
        });
    }
};
