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
        Schema::create('seg_grupo', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('usuario_id')->unsigned()->nullable();
            $table->integer('perfil_id')->unsigned()->nullable();

            $table->foreign('usuario_id')
                ->references('id')
                ->on('usuario');

            $table->foreign('perfil_id')
                ->references('id')
                ->on('seg_perfil');

            $table->unique(['usuario_id', 'perfil_id']);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('seg_grupo');
    }
};
