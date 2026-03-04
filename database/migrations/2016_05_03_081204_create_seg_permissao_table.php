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
        Schema::create('seg_permissao', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('acao_id')->unsigned()->nullable();
            $table->integer('perfil_id')->unsigned()->nullable();
            $table->timestamps();

            $table->foreign('acao_id')
                ->references('id')
                ->on('seg_acao');

            $table->foreign('perfil_id')
                ->references('id')
                ->on('seg_perfil');

            $table->unique(['acao_id', 'perfil_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('seg_permissao');
    }
};
