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
        Schema::create('seguranca.sistema', function (Blueprint $table) {
            $table->id();
            $table->string('nome', 45);
            $table->string('link', 100);
            $table->string('imagem', 50);
            $table->char('uuid', 36);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('seguranca.sistema');
    }
};
