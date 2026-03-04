<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('usuario_tipo_cardapio', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('fk_usuario')->unsigned();
            $table->integer('fk_tipo_cardapio')->unsigned();

            $table->foreign('fk_usuario')
                 ->references('id')
                 ->on('usuario');

            $table->foreign('fk_tipo_cardapio')
                ->references('id')
                ->on('cardapio_tipo');

            $table->unique(['fk_usuario', 'fk_tipo_cardapio']);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('usuario_tipo_cardapio');
    }
};
