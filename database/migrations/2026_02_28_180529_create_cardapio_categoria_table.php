<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cardapio_categoria', function (Blueprint $table) {
            $table->increments('id');

            $table->string('nome');
            $table->integer('fk_tipo_cardapio')->unsigned();
            $table->string('nome_en', 150)->nullable();
            $table->timestamps();

            $table->foreign('fk_tipo_cardapio')
                ->references('id')
                ->on('cardapio_tipo');

            $table->index('fk_tipo_cardapio');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cardapio_categoria');
    }
};
