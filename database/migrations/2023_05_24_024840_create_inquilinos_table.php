<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInquilinosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inquilinos', function (Blueprint $table) {
            $table->id();
            $table->string('nombres');
            $table->string('apellidos');
            $table->string('ci');
            $table->date('fecha_inicio_contrato');
            $table->date('fecha_fin_contrato')->nullable();
            $table->boolean('permiso')->default(false);
            $table->tinyInteger('estado')->default(1)->comment('1 = activo, 2 = inactivo');
            $table->foreignId('user_id')->constrained('users');
            $table->foreignId('residente_id')->constrained('residentes');
            $table->string('src_foto', 100)->nullable();
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
        Schema::dropIfExists('inquilinos');
    }
}
