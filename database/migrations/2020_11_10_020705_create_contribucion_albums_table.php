<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateContribucionAlbumsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contribucion_albums', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->longText('image');
            $table->date('anio');
            $table->unsignedBigInteger('id_genero');
            $table->foreign('id_genero')->references('id')->on('genero');
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
        Schema::dropIfExists('contribucion_albums');
    }
}
