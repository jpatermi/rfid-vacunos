<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDiseaseTreatmentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('disease_treatment', function (Blueprint $table) {
            $table->increments('id')->comment('Llave primaria y ID de la tabla');
            $table->integer('disease_id')->unsigned()->comment('ID de la Enfermedad que se le aplica el Tratamiento');
            $table->integer('treatment_id')->unsigned()->comment('ID del Tratamiento aplicado a la Enfermedad');
            $table->string('indication', 100)->comment('Indicaciones del Tratamiento');
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('disease_id')->references('id')->on('diseases')->onDelete('cascade');
            $table->foreign('treatment_id')->references('id')->on('treatments');
            $table->unique(['disease_id', 'treatment_id', 'deleted_at'], 'uk_disease_treatment');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('disease_treatment');
    }
}
