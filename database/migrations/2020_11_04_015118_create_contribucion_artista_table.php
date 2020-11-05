<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateContribucionArtistaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contribucion_artista', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->longText('image');
            $table->unsignedBigInteger('id_pais');
            $table->unsignedBigInteger('id_user');
            $table->foreign('id_pais')->references('id')->on('paises');
            $table->foreign('id_user')->references('id')->on('users');
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
        Schema::dropIfExists('contribucion_artista');
    }
}
