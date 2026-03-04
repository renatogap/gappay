<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cardapio_tipo', function (Blueprint $table) {
            $table->increments('id');

            $table->string('nome');

            $table->binary('foto')->nullable();
            $table->binary('thumbnail')->nullable();

            $table->string('nome_foto')->nullable();
            $table->string('type')->nullable();
            $table->integer('size')->nullable();
            $table->integer('status')->default(1);
            $table->string('apelido')->nullable();
            $table->integer('estoque')->nullable();
            
            $table->index('status');
            
            $table->timestamps();
            $table->timestamp('deleted_at')->nullable();
        });

        // depois altera para LONGBLOB
        DB::statement('ALTER TABLE cardapio_tipo MODIFY foto LONGBLOB NULL');
        DB::statement('ALTER TABLE cardapio_tipo MODIFY thumbnail LONGBLOB NULL');
    }

    public function down(): void
    {
        Schema::dropIfExists('cardapio_tipo');
    }
};
