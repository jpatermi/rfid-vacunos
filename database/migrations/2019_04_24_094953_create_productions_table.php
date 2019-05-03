<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('productions', function (Blueprint $table) {
            $table->increments('id')->comment('Llave primaria y ID de la tabla');
            $table->integer('animal_id')->unsigned()->comment('ID del Animal que se le registra la Producción');
            $table->decimal('colostrum', 8, 2)->default(0)->comment('Calostro en Ltr producidos por el Animal');
            $table->decimal('milk', 8, 2)->default(0)->comment('Leche en Ltr producidos por el Animal');
            $table->date('production_date')->comment('Fecha en la que se registra la Producción');
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('animal_id')->references('id')->on('animals');
            $table->unique(['animal_id', 'colostrum', 'milk', 'deleted_at'], 'uk_productions');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('productions');
    }
}
