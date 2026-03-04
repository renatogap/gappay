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
        Schema::create('policia.bairro', function (Blueprint $table) {
            $table->id();
            $table->char('uf', 2);
            $table->foreignId('cidade_id')->nullable()->constrained('policia.cidade');
            $table->string('nome', 200);
            $table->string('nome_abrev', 150);

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('policia.bairro');
    }
};
