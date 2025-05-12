<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('comanda_prod', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('idComanda');
            $table->unsignedBigInteger('idProducte');
            $table->integer('cant');
            $table->boolean('has_to_comment');
            $table->foreign('idComanda')->references('id')->on('comanda');
            $table->foreign('idProducte')->references('id')->on('producte');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('comanda_prod');
    }
};