<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddWeightHeightToAnimalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('animals', function (Blueprint $table) {
            $table->decimal('last_weight', 8, 2)->after('father_rfid')->default(0)->comment('Guarda el último Peso en KG del Animal');
            $table->integer('last_height')->unsigned()->after('last_weight')->default(0)->comment('Guarda la última Altura en CM del Animal');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('animals', function (Blueprint $table) {
            $table->dropColumn('last_weight', 'last_height');
        });
    }
}
