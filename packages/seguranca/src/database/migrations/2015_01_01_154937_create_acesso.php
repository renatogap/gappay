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
        Schema::create('seguranca.acesso', function (Blueprint $table) {
            $table->id();
            $table->foreignId('fk_usuario')->nullable()->constrained('usuario');
            $table->string('ip', 15);
            $table->dateTime('login');
            $table->dateTime('logout')->nullable();
            $table->string('user_agent');
            $table->dateTime('ultimo_acesso')->nullable();
            $table->string('session_id');
            $table->foreignId('fk_sistema_login')->nullable()->constrained('seguranca.sistema');
            $table->foreignId('fk_sistema_logout')->nullable()->constrained('seguranca.sistema');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('seguranca.acesso');
    }
};
