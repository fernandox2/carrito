<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDetallesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('detalles', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('venta_id')->unsigned();
            $table->bigInteger('producto_id')->unsigned();
            $table->integer('cantidad');
            $table->integer('total');
            $table->timestamps();
            $table->foreign('venta_id')->references('id')->on('ventas')->onDelete('cascade');
            $table->foreign('producto_id')->references('id')->on('productos');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('detalles');
    }
}
