<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cardapio', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('fk_tipo_cardapio')->unsigned();
            $table->integer('fk_categoria')->unsigned();
            $table->integer('fk_produto')->unsigned();
            $table->string('nome_item')->nullable();
            $table->string('detalhe_item')->nullable();
            $table->decimal('valor', 10, 2);
            $table->decimal('unid', 10, 3);
            $table->integer('status')->default(1);
            $table->integer('cozinha')->default(1);
            $table->string('nome_item_en', 180)->nullable();
            $table->string('detalhe_item_en', 300)->nullable();
            $table->timestamps();

            $table->foreign('fk_tipo_cardapio')->references('id')->on('cardapio_tipo');
            $table->foreign('fk_categoria')->references('id')->on('cardapio_categoria');
            $table->foreign('fk_produto')->references('id')->on('produto');
            

            $table->index(['fk_tipo_cardapio', 'fk_produto']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cardapio');
    }
};
