<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cardapio_foto', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('fk_cardapio')->unsigned();
            $table->binary('foto')->nullable();
            $table->binary('thumbnail')->nullable();
            $table->string('nome')->nullable();
            $table->string('type')->nullable();
            $table->integer('size')->nullable();
            $table->timestamps();

            $table->foreign('fk_cardapio')
                ->references('id')
                ->on('cardapio');

            $table->index('fk_cardapio');
        });

        // Alterando para LONGBLOB
        DB::statement('ALTER TABLE cardapio_foto MODIFY foto LONGBLOB NULL');
        DB::statement('ALTER TABLE cardapio_foto MODIFY thumbnail LONGBLOB NULL');
    }

    public function down(): void
    {
        Schema::dropIfExists('cardapio_foto');
    }
};
