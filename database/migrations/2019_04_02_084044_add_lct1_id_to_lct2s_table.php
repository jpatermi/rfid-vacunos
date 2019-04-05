<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddLct1IdToLct2sTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('lct2s', function (Blueprint $table) {
            $table->unsignedInteger('lct1_id')->default(1)->after('name')->comment('ID de la 1era Ubicación a la que pertenece la 2da Ubicación');
            $table->foreign('lct1_id')->references('id')->on('lct1s');
            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('lct2s', function (Blueprint $table) {
            $table->dropColumn('lct1_id');
        });
    }
}
