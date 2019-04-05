<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAreaIdToLct1sTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('lct1s', function (Blueprint $table) {
            $table->unsignedInteger('area_id')->default(1)->after('name')->comment('ID del Area a la que pertenece la 1era Ubicación');
            $table->foreign('area_id')->references('id')->on('areas');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('lct1s', function (Blueprint $table) {
            $table->dropColumn('area_id');
        });
    }
}
