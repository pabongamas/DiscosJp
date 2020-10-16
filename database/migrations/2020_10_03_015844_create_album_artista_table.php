<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAlbumArtistaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('album_artista', function (Blueprint $table) {
            $table->unsignedBigInteger('artista_id');
            $table->unsignedBigInteger('album_id');
            $table->foreign('artista_id')->references('id')->on('artistas');
            $table->foreign('album_id')->references('id')->on('album');
            $table->boolean('estado')->nullable();
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
        Schema::dropIfExists('album_artista');
    }
}
