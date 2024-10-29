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
        Schema::table('communes', function (Blueprint $table) {
            $table->unsignedBigInteger("arrondissement_id")->nullable();
            $table->foreign("arrondissement_id")
            ->references("id")
            ->on("arrondissements");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('communes', function (Blueprint $table) {
            $table->dropForeign("arrondissement_id");
            $table->dropColumn("arrondissement_id");
        });
    }
};
