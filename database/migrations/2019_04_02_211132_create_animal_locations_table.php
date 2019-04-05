<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAnimalLocationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('animal_locations', function (Blueprint $table) {
            $table->increments('id')->comment('Llave primaria y ID de la tabla');
            $table->integer('animal_id')->unsigned()->comment('ID del Animal que se le guarda el movimiento');
            $table->integer('farm_id')->unsigned()->comment('ID de la hacienda o Granja a la que pertenece el Animal');
            $table->integer('area_id')->unsigned()->comment('ID del Área a la que pertenece el Animal');
            $table->integer('lct1_id')->unsigned()->comment('ID de la Ubicación 1 a la que pertenece el Animal');
            $table->integer('lct2_id')->unsigned()->comment('ID de la Ubicación 2 a la que pertenece el Animal');
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('animal_id')->references('id')->on('animals');
            $table->foreign('farm_id')->references('id')->on('farms');
            $table->foreign('area_id')->references('id')->on('areas');
            $table->foreign('lct1_id')->references('id')->on('lct1s');
            $table->foreign('lct2_id')->references('id')->on('lct2s');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('animal_locations');
    }
}
