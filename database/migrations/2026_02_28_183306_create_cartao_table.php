<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cartao', function (Blueprint $table) {
            $table->increments('id');
            $table->string('codigo');
            $table->string('hash');
            $table->date('data');
            $table->integer('fk_situacao')->unsigned();
            $table->integer('cartao_gerado')->default(0);
            $table->timestamp('dt_geracao_cartao')->nullable();
            $table->timestamps();

            $table->foreign('fk_situacao')
                ->references('id')
                ->on('situacao_cartao');

            $table->index('fk_situacao');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cartao');
    }
};
