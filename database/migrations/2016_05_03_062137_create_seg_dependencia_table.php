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
    public function up(): void
    {
        Schema::create('seg_dependencia', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('acao_atual_id')->unsigned()->nullable();
            $table->integer('acao_dependencia_id')->unsigned()->nullable();

            $table->foreign('acao_atual_id')
                ->references('id')
                ->on('seg_acao');

            $table->foreign('acao_dependencia_id')
                ->references('id')
                ->on('seg_acao');

            $table->timestamps();
            
            $table->unique(['acao_atual_id', 'acao_dependencia_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('seg_dependencia');
    }
};
