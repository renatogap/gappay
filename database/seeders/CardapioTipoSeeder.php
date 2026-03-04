<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CardapioTipoSeeder extends Seeder
{
    public function run(): void
    {
        $agora = date('Y-m-d H:i:s');
        $items = [
            'id' => 1,
            'nome' => 'Lanchonete',
            'status' => true,
            'created_at' => $agora
        ];

        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('cardapio_tipo')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        DB::table('cardapio_tipo')->insert($items);
    }
}
