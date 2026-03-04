<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
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
        $schema = schema('conexao_padrao');
        DB::statement("create SCHEMA IF NOT EXISTS $schema");
        DB::statement("create SCHEMA IF NOT EXISTS seguranca");
        DB::statement("create SCHEMA IF NOT EXISTS policia");
        DB::statement("create SCHEMA IF NOT EXISTS srh");
        DB::statement('create extension IF NOT EXISTS dblink SCHEMA public');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement('drop SCHEMA seguranca');
        DB::statement('drop SCHEMA policia');
        DB::statement('drop SCHEMA srh');
        DB::statement('drop extension dblink');
    }
};
