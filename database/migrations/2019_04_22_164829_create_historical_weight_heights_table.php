<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHistoricalWeightHeightsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('historical_weight_heights', function (Blueprint $table) {
            $table->increments('id')->comment('Llave primaria y ID de la tabla');
            $table->integer('animal_id')->unsigned()->comment('ID del Animal que se le aplica la medición');
            $table->decimal('weight', 8, 2)->default(0)->comment('Peso en Kg del Animal');
            $table->integer('height')->unsigned()->default(0)->comment('Altura en Cm del Animal');
            $table->date('measurement_date')->comment('Fecha en la que se toma la medición');
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('animal_id')->references('id')->on('animals');
            $table->unique(['animal_id', 'weight', 'height', 'deleted_at'], 'uk_historical_weights_heights');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('historical_weight_heights');
    }
}
