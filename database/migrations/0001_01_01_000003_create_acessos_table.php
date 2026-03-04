<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('acesso', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('fk_usuario')->unsigned();
            $table->integer('fk_sistema_login')->unsigned();

            $table->foreign('fk_usuario')
                ->references('id')
                ->on('usuario');

            $table->foreign('fk_sistema_login')
                ->references('id')
                ->on('sistema');

            $table->string('ip', 45)->nullable();
            $table->timestamp('login', 6)->nullable();
            $table->timestamp('logout', 6)->nullable();
            $table->string('user_agent')->nullable();
            $table->timestamp('ultimo_acesso', 6)->nullable();
            $table->string('session_id', 40)->nullable();

            $table->index('fk_usuario');
            $table->index('session_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('acesso');
    }
};
