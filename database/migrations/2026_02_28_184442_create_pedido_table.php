<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pedido', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('fk_cartao')->unsigned();
            $table->integer('fk_cartao_cliente')->unsigned();
            $table->integer('mesa')->nullable();
            $table->decimal('taxa_servico', 10, 2)->nullable();
            $table->decimal('valor_total', 10, 2);
            $table->timestamp('dt_pedido')->useCurrent();
            $table->timestamp('dt_pronto')->nullable();
            $table->timestamp('dt_entrega')->nullable();
            $table->integer('status')->unsigned();
            $table->integer('fk_usuario')->unsigned();
            $table->timestamps();
            $table->foreign('fk_cartao')->references('id')->on('cartao');
            $table->foreign('fk_cartao_cliente')->references('id')->on('cartao_cliente');
            $table->foreign('fk_usuario')->references('id')->on('usuario');
            $table->foreign('status')->references('id')->on('situacao_pedido');

            $table->index(['fk_cartao_cliente', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pedido');
    }
};
