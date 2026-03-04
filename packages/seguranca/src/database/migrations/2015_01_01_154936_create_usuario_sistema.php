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
        Schema::create('usuario_sistema', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sistema_id')->nullable()->constrained('seguranca.sistema');
            $table->foreignId('usuario_id')->nullable()->constrained('usuario');
            $table->date('ultimo_acesso');
            $table->integer('status');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('usuario_sistema');
    }
};
