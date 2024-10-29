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
        Schema::table('users', function (Blueprint $table) {
            $table->unsignedBigInteger("departement_id")->nullable();
            $table->foreign("departement_id")
            ->references("id")
            ->on("departements");
            $table->unsignedBigInteger("region_id")->nullable();
            $table->foreign("region_id")
            ->references("id")
            ->on("regions");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign("departement_id");
            $table->dropColumn("departement_id");

            $table->dropForeign("region_id");
            $table->dropColumn("region_id");
        });
    }
};
