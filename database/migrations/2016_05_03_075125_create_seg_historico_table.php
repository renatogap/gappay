<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('seg_historico', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('usuario_id')->unsigned()->nullable();
            $table->integer('acao_id')->unsigned()->nullable();

            $table->foreign('usuario_id')
                ->references('id')
                ->on('usuario');

            $table->foreign('acao_id')
                ->references('id')
                ->on('seg_acao');

            $table->jsonb('antes')->nullable();
            $table->jsonb('depois')->nullable();
            $table->string('ip', 45);
            $table->string('url', 2048);//tamanho máximo do get no protocolo http
            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('seg_historico');
    }
};
