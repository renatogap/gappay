<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('usuario_sistema', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('usuario_id')->unsigned();
            $table->integer('sistema_id')->unsigned();
            $table->integer('fk_usuario_cadastro')->unsigned();
            $table->integer('fk_usuario_edicao')->unsigned()->nullable();
            $table->date('ultimo_acesso')->nullable();
            $table->boolean('ativo')->default(true);
            $table->boolean('status')->default(true);
            $table->timestamps();

            $table->foreign('sistema_id')->references('id')->on('sistema');
            $table->foreign('usuario_id')->references('id')->on('usuario');
            $table->foreign('fk_usuario_cadastro')->references('id')->on('usuario');
            $table->foreign('fk_usuario_edicao')->references('id')->on('usuario');
                
            $table->unique(['usuario_id', 'sistema_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('usuario_sistema');
    }
};
