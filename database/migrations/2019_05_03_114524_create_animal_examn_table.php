<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAnimalExamnTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('animal_examn', function (Blueprint $table) {
            $table->increments('id')->comment('Llave primaria y ID de la tabla');
            $table->integer('animal_id')->unsigned()->comment('ID del Animal que se le realiza el Examen');
            $table->integer('examn_id')->unsigned()->comment('ID del Examen que se realiza al Animal');
            $table->date('application_date')->comment('Fecha de realización del Examen');
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('animal_id')->references('id')->on('animals');
            $table->foreign('examn_id')->references('id')->on('examns');
            $table->unique(['animal_id', 'examn_id', 'application_date'], 'uk_animal_examn');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('animal_examn');
    }
}
