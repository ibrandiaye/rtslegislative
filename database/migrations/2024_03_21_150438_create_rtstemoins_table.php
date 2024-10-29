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
        Schema::create('rtstemoins', function (Blueprint $table) {
            $table->id();
            $table->string('nbvote');
            $table->unsignedBigInteger('candidat_id');
            $table->unsignedBigInteger('lieuvote_id');
            $table->foreign('lieuvote_id')->references('id')->on('lieuvotes')->onDelete('cascade');
            $table->foreign('candidat_id')->references('id')->on('candidats')->onDelete('cascade');

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
        Schema::dropIfExists('rtstemoins');
    }
};
