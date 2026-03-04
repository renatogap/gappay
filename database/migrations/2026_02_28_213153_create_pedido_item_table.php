<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pedido_item', function (Blueprint $table) {

            $table->increments('id');

            $table->integer('fk_pedido')->unsigned();
            $table->integer('fk_item_cardapio')->unsigned();
            $table->decimal('valor', 10, 2);
            $table->decimal('quantidade', 10, 3);
            $table->text('observacao')->nullable();
            $table->integer('status')->comment('1-SOLICITADO | 2-PRONTO | 3-CANCELADO');
            $table->timestamp('dt_pronto')->nullable();
            $table->timestamp('dt_entregue')->nullable();
            $table->integer('visto_pela_cozinha')->nullable();
            $table->integer('visto_pelo_promotor')->nullable();
            $table->integer('fk_usuario_cancelamento')->unsigned()->nullable();
            $table->timestamp('dt_cancelamento')->nullable();
            $table->timestamps();

            $table->foreign('fk_pedido')
                ->references('id')
                ->on('pedido');

            $table->foreign('fk_item_cardapio')
                ->references('id')
                ->on('cardapio');

            $table->foreign('fk_usuario_cancelamento')
                ->references('id')
                ->on('usuario');

            $table->index(['fk_pedido', 'fk_item_cardapio', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pedido_item');
    }
};
