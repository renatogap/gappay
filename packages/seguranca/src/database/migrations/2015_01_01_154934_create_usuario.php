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
    public function up()
    {
        Schema::create('usuario', function (Blueprint $table) {
            $table->id();
            $table->string('nome', 200);
            $table->string('email', 200)->unique();
            $table->string('senha', 50);
            $table->string('login', 100)->nullable();
            $table->date('dt_cadastro');
            $table->boolean('excluido')->default(false);
            $table->boolean('primeiro_acesso')->default(false);
            $table->char('cpf', 11)->unique()->nullable();
            $table->date('nascimento')->nullable();
            $table->string('remember_token', 100)->nullable();
            $table->string('unidade')->nullable();
            $table->string('status')->default(1);
            $table->string('fk_usuario_correicao')->nullable();
            $table->char('senha2', 60)->nullable();
            $table->boolean('diretor')->default(false);
            $table->integer('fk_unidade')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('usuario');
    }
};
