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
        Schema::create('policia.cidade', function (Blueprint $table) {
            $table->id();
            $table->string('nome');
            $table->string('nome_det');
            $table->string('cep', 8);
            $table->char('uf', 2);
            $table->smallInteger('situacao');
            $table->string('codigo_ibge', 7);

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('policia.cidade');
    }
};
