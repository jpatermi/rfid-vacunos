<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAnimalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('animals', function (Blueprint $table) {
            $table->increments('id')->comment('Llave primaria y ID de la tabla');
            $table->string('animal_rfid', 10)->comment('Identificación del Animal por el Tag electrónico');
            $table->boolean('photo')->default(false)->comment('Indica se el Animal tiene Foto almacenada en una ruta');
            $table->enum('gender', ['M', 'H'])->comment('Género del Animal "M" o "H"');
            $table->date('birthdate')->comment('Fecha de nacimiento del Animal');
            $table->integer('breed_id')->unsigned()->comment('ID de la Raza a la que pertenece el Animal');
            $table->decimal('weight', 8, 2)->default(0)->comment('Peso en KG del Animal');
            $table->integer('height')->unsigned()->default(0)->comment('Altura en CM del Animal');
            $table->integer('farm_id')->unsigned()->comment('ID de la Hacienda o Granja a la que pertenece el Animal');
            $table->integer('area_id')->unsigned()->nullable()->comment('ID del Área donde se encuentra el Animal');
            $table->integer('lct1_id')->unsigned()->nullable()->comment('ID de la 1era Ubicación dentro del Área donde se encuentra el Animal');
            $table->integer('lct2_id')->unsigned()->nullable()->comment('ID de la 2da Ubicación dentro del Área donde se encuentra el Animal');
            $table->integer('lct3_id')->unsigned()->nullable()->comment('ID de la 3era Ubicación dentro del Área donde se encuentra el Animal');
            $table->integer('user_id')->unsigned()->comment('ID del Usuario que registra o actualiza los datos del Animal');
            $table->timestamps();
            $table->foreign('breed_id')->references('id')->on('breeds');
            $table->foreign('farm_id')->references('id')->on('farms');
            $table->foreign('area_id')->references('id')->on('areas');
            $table->foreign('lct1_id')->references('id')->on('lct1s');
            $table->foreign('lct2_id')->references('id')->on('lct2s');
            $table->foreign('lct3_id')->references('id')->on('lct3s');
            $table->foreign('user_id')->references('id')->on('users');
            $table->unique(['animal_rfid', 'farm_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('animals');
    }
}
