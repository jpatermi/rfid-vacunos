<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateExamnsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('examns', function (Blueprint $table) {
            $table->increments('id')->comment('Llave primaria y ID de la tabla');
            $table->string('name', 100)->comment('Nombre del Examen');
            $table->string('characteristic', 100)->comment('Característica del Examen');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('examns');
    }
}
