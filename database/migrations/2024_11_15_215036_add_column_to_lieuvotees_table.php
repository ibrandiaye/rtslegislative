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
        Schema::table('lieuvotees', function (Blueprint $table) {
            $table->integer("votant");
            $table->integer("bulnull");
            $table->integer("hs");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('lieuvotees', function (Blueprint $table) {
            $table->dropColumn("votant");
            $table->dropColumn("bulnull");
            $table->dropColumn("hs");
        });
    }
};
