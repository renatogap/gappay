<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('estoque', function (Blueprint $table) {
            $table->increments('id');
            $table->char('tipo_movimento', 1)->nullable();
            $table->integer('fk_tipo_cardapio')->unsigned();
            $table->integer('fk_item_cardapio')->unsigned();
            $table->integer('qtd_atual');
            $table->integer('estoque_minimo')->nullable();
            $table->integer('estoque_maximo')->nullable();
            $table->integer('fk_tipo_unidade_medida')->unsigned()->comment('1-UNIDADE | 2-GARRAFA | 3-QUILO');
            $table->string('qtd_dose_por_garrafa', 5)->nullable();
            $table->timestamp('dt_ultima_atualizacao')->nullable();
            $table->integer('fk_usuario_cad')->unsigned();
            $table->integer('fk_usuario_alt')->unsigned()->nullable();
            $table->timestamps();

            $table->foreign('fk_tipo_cardapio')->references('id')->on('cardapio_tipo');
            $table->foreign('fk_item_cardapio')->references('id')->on('cardapio');
            $table->foreign('fk_tipo_unidade_medida')->references('id')->on('tipo_unidade_medida');
            $table->foreign('fk_usuario_cad')->references('id')->on('usuario');
            $table->foreign('fk_usuario_alt')->references('id')->on('usuario');

            $table->index(['fk_tipo_cardapio', 'fk_item_cardapio']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('estoque');
    }
};
