<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('estoque_entrada', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('fk_tipo_cardapio')->unsigned();
            $table->integer('fk_item_cardapio')->unsigned();
            $table->integer('quantidade');
            $table->decimal('valor_unitario', 10, 2);
            $table->decimal('valor_total', 10, 2);
            $table->text('observacao')->nullable();
            $table->integer('fk_usuario_cad')->unsigned();
            $table->integer('fk_usuario_alt')->unsigned()->nullable();
            $table->timestamps();

            $table->foreign('fk_tipo_cardapio')->references('id')->on('cardapio_tipo');
            $table->foreign('fk_item_cardapio')->references('id')->on('cardapio');
            $table->foreign('fk_usuario_cad')->references('id')->on('usuario');
            $table->foreign('fk_usuario_alt')->references('id')->on('usuario');

            $table->index(['fk_tipo_cardapio', 'fk_item_cardapio']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('estoque_entrada');
    }
};
