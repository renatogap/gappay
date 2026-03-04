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
        Schema::create('policia.unidade', function (Blueprint $table) {
            $table->id();
            $table->string('nome');
            $table->string('endereco')->nullable();
            $table->string('complemento')->nullable();
            $table->char('cep', 10)->nullable();
            $table->string('numero', 45)->nullable();
            $table->foreignId('bairro_id')->nullable()->constrained('policia.bairro');
            $table->foreignId('cidade_id')->nullable()->constrained('policia.cidade');
            $table->double('lat')->nullable();
            $table->double('lng')->nullable();
            $table->string('email')->nullable();
            $table->string('sigla', 45)->nullable();
            $table->string('sede', 45)->nullable();
            $table->string('codigo', 45)->nullable();
            $table->char('status');
            $table->integer('unidade_id');
            $table->string('telefone', 45);
            $table->string('telefone2', 45);
            $table->string('ip_rede', 100);
            $table->string('ip_satelite', 100);
            $table->string('ip_navega_para', 100);
            $table->string('circuito_fisp', 100);
            $table->smallInteger('link');
            $table->string('contato', 100);
            $table->text('observacao');
            $table->integer('tipo_unidade_id');
            $table->integer('unidade_pai_id');
            $table->boolean('orgao_externo');
            $table->integer('fk_unidade_sisp');
            $table->integer('fk_risp');
            $table->boolean('predio_dg');
            $table->char('tipo_horario');
            $table->string('horario');
            $table->string('jornada_trabalho');
            $table->integer('dpc_ideal');
            $table->integer('epc_ideal');
            $table->integer('ipc_ideal');
            $table->integer('ppc_ideal');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('policia.unidade');
    }
};
