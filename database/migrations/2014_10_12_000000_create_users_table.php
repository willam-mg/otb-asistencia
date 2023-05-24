<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

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
            $table->id();
            $table->string('nombre');
            $table->string('apellido')->nullable();
            $table->text('direccion')->nullable();
            $table->string('telefono')->nullable();
            $table->string('celular')->nullable();
            $table->string('numero_domicilio')->nullable();
            $table->string('calle')->nullable();
            $table->string('foto', 100)->nullable();
            $table->tinyInteger('estado')->default(1)->comment('1 = activo, 2 = inactivo');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->foreignId('cargo_id')->nullable()->constrained('cargos');
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
        Schema::dropIfExists('users');
    }
}
