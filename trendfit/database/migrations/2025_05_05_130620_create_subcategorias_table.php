<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('subcategoria', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('descr');
            $table->unsignedBigInteger('idCategoria');
            $table->foreign('idCategoria')->references('id')->on('categoria');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('subcategoria');
    }
};