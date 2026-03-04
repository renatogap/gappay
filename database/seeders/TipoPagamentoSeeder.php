<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TipoPagamentoSeeder extends Seeder
{
    public function run(): void
    {
        $items = [
            ['id' => 1, 'nome' => 'DINHEIRO', 'created_at' => now()],
            ['id' => 2, 'nome' => 'DÉBITO', 'created_at' => now()],
            ['id' => 3, 'nome' => 'CRÉDITO', 'created_at' => now()],
            ['id' => 4, 'nome' => 'ESTORNO', 'created_at' => now()],
            ['id' => 5, 'nome' => 'TRANSFERÊNCIA', 'created_at' => now()],
            ['id' => 6, 'nome' => 'PIX', 'created_at' => now()]
        ];

        DB::table('tipo_pagamento')->insert($items);
    }
}
