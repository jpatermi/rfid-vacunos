<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id')->comment('Llave primaria y ID de la tabla');
            $table->string('name', 50)->comment('Nombre y Apellido del usuario');
            $table->string('username', 15)->comment('usuario para el Login');
            $table->string('email', 50)->unique()->comment('Dirección de correo electrónico');
            $table->string('password')->comment('Clave de acceso');
            $table->rememberToken()->comment('Token por si se desea mantener logueado');
            $table->string('api_token', 60)->unique()->comment('Token de autorización para el API');
            $table->timestamps();//->comment('Fecha y hora de creación y actualización');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
