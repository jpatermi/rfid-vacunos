<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDiseasesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('diseases', function (Blueprint $table) {
            $table->increments('id')->comment('Llave primaria y ID de la tabla');
            $table->integer('animal_id')->unsigned()->comment('ID del Animal que se le aplica el Diagnóstico');
            $table->integer('veterinarian_id')->unsigned()->comment('ID del Veterinario que hace el Diagnóstico');
            $table->integer('diagnostic_id')->unsigned()->comment('ID del Diagnóstico encontrado');
            $table->integer('cause_id')->unsigned()->comment('ID de la Causa del Diagnóstico encontrado');
            $table->integer('responsible_id')->unsigned()->comment('ID del Responsable de aplicar el Tratamiento');
            $table->date('review_date')->comment('Fecha de la revisión Médica');
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('animal_id')->references('id')->on('animals');
            $table->foreign('veterinarian_id')->references('id')->on('veterinarians');
            $table->foreign('diagnostic_id')->references('id')->on('diagnostics');
            $table->foreign('cause_id')->references('id')->on('causes');
            $table->foreign('responsible_id')->references('id')->on('responsibles');
            $table->unique(['animal_id', 'diagnostic_id', 'review_date'], 'uk_animal_disease');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('diseases');
    }
}
