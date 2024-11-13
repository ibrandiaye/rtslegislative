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
        Schema::table('lieuvotes', function (Blueprint $table) {
            $table->boolean("heure1")->default(false);
            $table->boolean("heure2")->default(false);
            $table->boolean("heure3")->default(false);
            $table->boolean("heure4")->default(false);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('lieuvotes', function (Blueprint $table) {
            $table->dropColumn("heure1");
            $table->dropColumn("heure2");
            $table->dropColumn("heure3");
            $table->dropColumn("heure4");
        });
    }
};
