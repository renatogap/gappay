<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('estoque_saida', function (Blueprint $table) {
            $table->integer('id')->autoIncrement();

            $table->integer('fk_tipo_cardapio')->unsigned()->nullable();
            $table->integer('fk_item_cardapio')->unsigned()->nullable();
            $table->integer('quantidade')->nullable();
            $table->integer('fk_pedido_item')->unsigned()->nullable();

            $table->text('observacao')->nullable();
            $table->integer('fk_usuario')->unsigned()->nullable();

            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();

            $table->foreign('fk_tipo_cardapio')->references('id')->on('cardapio_tipo');
            $table->foreign('fk_item_cardapio')->references('id')->on('cardapio');
            $table->foreign('fk_pedido_item')->references('id')->on('pedido_item');
            $table->foreign('fk_usuario')->references('id')->on('usuario');

            $table->index(['fk_tipo_cardapio', 'fk_item_cardapio']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('estoque_saida');
    }
};
