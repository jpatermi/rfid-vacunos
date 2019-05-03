<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAnimalVitaminTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('animal_vitamin', function (Blueprint $table) {
            $table->increments('id')->comment('Llave primaria y ID de la tabla');
            $table->integer('animal_id')->unsigned()->comment('ID del Animal que se le aplica la Vitamina');
            $table->integer('vitamin_id')->unsigned()->comment('ID de la Vitamina que se aplica al Animal');
            $table->decimal('dose', 5, 2)->default(0.00)->comment('Dosis aplicada de la Vitamina al Animal');
            $table->date('application_date')->comment('Fecha de aplicación de la Vitamina');
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('animal_id')->references('id')->on('animals');
            $table->foreign('vitamin_id')->references('id')->on('vitamins');
            $table->unique(['animal_id', 'vitamin_id', 'application_date'], 'uk_animal_vitamin');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('animal_vitamin');
    }
}
