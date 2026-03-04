<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('entrada_credito', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('fk_cartao_cliente')->unsigned();
            $table->integer('fk_tipo_pagamento')->unsigned();
            $table->integer('fk_usuario')->unsigned();
            $table->decimal('valor', 10, 2);
            $table->longText('observacao')->nullable();
            $table->timestamp('data')->nullable();
            $table->timestamps();

            $table->foreign('fk_cartao_cliente')->references('id')->on('cartao_cliente');
            $table->foreign('fk_tipo_pagamento')->references('id')->on('tipo_pagamento');
            $table->foreign('fk_usuario')->references('id')->on('usuario');
            
            $table->index(['fk_cartao_cliente', 'fk_tipo_pagamento']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('entrada_credito');
    }
};
