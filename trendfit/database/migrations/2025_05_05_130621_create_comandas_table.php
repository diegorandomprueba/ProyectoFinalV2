<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('comanda', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('idUsuari');
            $table->string('name');
            $table->string('address');
            $table->string('city');
            $table->string('provincia');
            $table->string('codigo_postal');
            $table->date('date');
            $table->foreign('idUsuari')->references('id')->on('users');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('comanda');
    }
};