<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePhysicalCharacteristicsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('physical_characteristics', function (Blueprint $table) {
            $table->increments('id')->comment('Llave primaria y ID de la tabla');
            $table->integer('animal_id')->unsigned()->comment('ID del Animal que se le guarda la Característica');
            $table->string('characteristic', 100)->comment('Característica física del Animal');
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('animal_id')->references('id')->on('animals');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('physical_characteristics');
    }
}
