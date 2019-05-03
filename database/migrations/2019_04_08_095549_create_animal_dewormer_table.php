<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAnimalDewormerTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('animal_dewormer', function (Blueprint $table) {
            $table->increments('id')->comment('Llave primaria y ID de la tabla');
            $table->integer('animal_id')->unsigned()->comment('ID del Animal que se le aplica el Desparasitante');
            $table->integer('dewormer_id')->unsigned()->comment('ID del Desparasitante que se aplica al Animal');
            $table->decimal('dose', 5, 2)->default(0.00)->comment('Dosis aplicada del Desparasitante al Animal');
            $table->date('application_date')->comment('Fecha de aplicación del Desparasitante');
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('animal_id')->references('id')->on('animals');
            $table->foreign('dewormer_id')->references('id')->on('dewormers');
            $table->unique(['animal_id', 'dewormer_id', 'application_date'], 'uk_animal_dewormer');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('animal_dewormer');
    }
}
