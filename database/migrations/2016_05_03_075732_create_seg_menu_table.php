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
        Schema::create('seg_menu', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('acao_id')->unsigned()->nullable();
            $table->integer('pai')->unsigned()->nullable();
            $table->string('nome');
            $table->string('dica')->nullable();
            $table->string('icone')->nullable();
            $table->boolean('ativo')->default(true);
            $table->smallInteger('ordem');
            $table->jsonb('configuracoes')->nullable();
            $table->timestamps();

            $table->foreign('acao_id')
                ->references('id')
                ->on('seg_acao');

            $table->foreign('pai')
                ->references('id')
                ->on('seg_menu');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::drop('seg_menu');
    }
};
