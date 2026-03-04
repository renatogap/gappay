<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CardapioFotoSeeder extends Seeder
{
    public function run(): void
    {
        $agora = date('Y-m-d H:i:s');

        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('cardapio_foto')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        DB::statement("");
    }
}
