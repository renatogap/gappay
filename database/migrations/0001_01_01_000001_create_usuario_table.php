<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('usuario', function (Blueprint $table) {
            $table->increments('id');

            $table->string('nome');
            $table->string('email')->unique();
            $table->char('cpf', 11)->nullable();
            $table->date('nascimento')->nullable();
            $table->string('senha', 150)->nullable();
            $table->string('senha2', 150);
            $table->boolean('excluido')->default(false);
            $table->boolean('primeiro_acesso')->default(true);
            $table->timestamp('dt_cadastro', 6)->useCurrent();
            $table->rememberToken();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('usuario');
    }
};
