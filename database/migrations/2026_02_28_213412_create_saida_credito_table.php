<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('saida_credito', function (Blueprint $table) {

            $table->increments('id');
            $table->integer('fk_cartao_cliente')->unsigned()->nullable();
            $table->integer('fk_pedido')->unsigned()->nullable();
            $table->decimal('valor', 10, 2);
            $table->timestamp('data')->nullable();
            $table->text('observacao')->nullable();

            $table->integer('fk_usuario')->unsigned()->nullable();

            $table->foreign('fk_cartao_cliente')
                ->references('id')
                ->on('cartao_cliente');

            $table->foreign('fk_pedido')
                ->references('id')
                ->on('pedido');

            $table->foreign('fk_usuario')
                ->references('id')
                ->on('usuario');

            $table->index(['fk_cartao_cliente', 'fk_pedido']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('saida_credito');
    }
};
