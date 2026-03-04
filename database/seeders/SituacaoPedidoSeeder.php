<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SituacaoPedidoSeeder extends Seeder
{
    public function run(): void
    {
        $items = [
            ['id' => 1, 'nome' => 'Solicitado', 'created_at' => now()],
            ['id' => 2, 'nome' => 'Pronto', 'created_at' => now()],
            ['id' => 3, 'nome' => 'Entregue', 'created_at' => now()],
            ['id' => 4, 'nome' => 'Cancelado', 'created_at' => now()]
        ];

        DB::table('situacao_pedido')->insert($items);
    }
}
