<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddDoseToAnimalVaccinationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('animal_vaccination', function (Blueprint $table) {
            $table->decimal('dose', 5, 2)->default(0.00)->after('vaccination_id')->comment('Dosis aplicada de la Vacuna al Animal');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('dose', function (Blueprint $table) {
            //
        });
    }
}
