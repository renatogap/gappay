<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('responsavel', function (Blueprint $table) {
            $table->id();
            $table->string('nome')->nullable();
            $table->string('email')->unique();
            $table->string('token', 8)->nullable();
            $table->boolean('concordo')->default(false);
            $table->boolean('validado')->default(false);
            $table->string('telefone')->nullable();
            $table->string('senha')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('responsavel');
    }
};
