<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEmpresasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('empresas', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('rut');
            $table->string('razon_social');
            $table->string('rubro');
            $table->string('representante_legal');
            $table->string('correo');
            $table->string('imagen_corporativa')->nullable();
            $table->boolean('estado_licencia')->default(false);
            $table->boolean('ambiente_certificacion')->default(false);
            $table->string('ruta_firma_electronica')->nullable();
            $table->string('clave_firma')->nullable();
            $table->string('ciudad');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('empresas');
    }
}
