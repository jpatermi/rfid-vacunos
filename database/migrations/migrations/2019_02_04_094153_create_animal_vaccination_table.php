<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAnimalVaccinationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('animal_vaccination', function (Blueprint $table) {
            $table->increments('id')->comment('Llave primaria y ID de la tabla');
            $table->integer('animal_id')->unsigned()->comment('ID del Animal que se le aplica la Vacuna');
            $table->integer('vaccination_id')->unsigned()->comment('ID de la Vacuna que se aplica al Animal');
            $table->date('application_date')->comment('Fecha de aplicación de la Vacuna');
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('animal_id')->references('id')->on('animals');
            $table->foreign('vaccination_id')->references('id')->on('vaccinations');
            $table->unique(['animal_id', 'vaccination_id', 'application_date'], 'uk_animal_vaccination');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('animal_vaccination');
    }
}
