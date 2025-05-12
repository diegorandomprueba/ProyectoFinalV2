<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('producte', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('descr');
            $table->decimal('price', 10, 2);
            $table->integer('stock');
            $table->string('image', 255);
            $table->unsignedBigInteger('idCategoria');
            $table->foreign('idCategoria')->references('id')->on('subcategoria');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('producte');
    }
};