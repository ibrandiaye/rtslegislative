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
        Schema::create('bureaus', function (Blueprint $table) {
            $table->id();
            // ["nom","prenom","tel","fonction","commune_id","lieuvote_id"
            $table->string("nom");
            $table->string("prenom");
            $table->string("tel")->unique();
            $table->string("fonction");
            $table->unsignedBigInteger("commune_id");
            $table->unsignedBigInteger("lieuvote_id");
            $table->foreign("commune_id")
            ->references("id")
            ->on("communes");
            $table->foreign("lieuvote_id")
            ->references("id")
            ->on("lieuvotes");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bureaus');
    }
};
