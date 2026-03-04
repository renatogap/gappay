<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cartao_cliente', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('fk_cartao')->unsigned();
            $table->string('nome');
            $table->string('cpf', 11)->nullable();
            $table->string('telefone', 50)->nullable();
            $table->integer('fk_cliente_titular')->nullable();
            $table->integer('fk_dependente')->nullable();
            $table->integer('fk_tipo_cliente')->unsigned();
            $table->integer('fk_tipo_pagamento')->unsigned();
            $table->decimal('valor_atual', 10, 2);
            $table->decimal('valor_cartao', 10, 2)->nullable();
            $table->char('devolvido', 1)->default('N');
            $table->decimal('valor_devolvido', 10, 2)->nullable();
            $table->timestamp('dt_devolucao')->nullable();
            $table->integer('fk_cartao_transferido')->nullable();
            $table->text('observacao')->nullable();
            $table->text('notificacao')->nullable();
            $table->integer('status');
            $table->integer('fk_usuario')->unsigned();
            $table->timestamps();

            // Foreign Keys
            $table->foreign('fk_cartao')->references('id')->on('cartao');
            $table->foreign('fk_tipo_cliente')->references('id')->on('tipo_cliente');
            $table->foreign('fk_tipo_pagamento')->references('id')->on('tipo_pagamento');
            $table->foreign('fk_usuario')->references('id')->on('usuario');
            
            $table->index('fk_cartao');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cartao_cliente');
    }
};
