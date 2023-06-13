<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMultasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('multas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('residente_id')->constrained('residentes');
            $table->foreignId('inquilino_id')->nullable()->constrained('inquilinos');
            $table->text('descripcion');
            $table->decimal('monto', 11, 2);
            $table->date('fecha_emision');
            $table->date('fecha_cancelacion');
            $table->boolean('cancelado');
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
        Schema::dropIfExists('multas');
    }
}
