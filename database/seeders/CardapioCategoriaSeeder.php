<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CardapioCategoriaSeeder extends Seeder
{
    public function run(): void
    {
        $agora = date('Y-m-d H:i:s');
        $items = [
            ['id' => 180, 'fk_tipo_cardapio' => 1, 'nome' => 'Lanches',  'created_at' => $agora],
            ['id' => 181, 'fk_tipo_cardapio' => 1, 'nome' => 'Bebidas',  'created_at' => $agora],
            ['id' => 182, 'fk_tipo_cardapio' => 1, 'nome' => 'Doces',    'created_at' => $agora],
            ['id' => 183, 'fk_tipo_cardapio' => 1, 'nome' => 'Café',     'created_at' => $agora],
            ['id' => 184, 'fk_tipo_cardapio' => 1, 'nome' => 'Comidas',  'created_at' => $agora]
        ];

        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('cardapio_categoria')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        DB::table('cardapio_categoria')->insert($items);
    }
}
