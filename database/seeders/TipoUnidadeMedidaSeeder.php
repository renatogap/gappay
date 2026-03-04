<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TipoUnidadeMedidaSeeder extends Seeder
{
    public function run(): void
    {
        $items = [
            ['id' => 1, 'nome' => 'Unidade', 'created_at' => now()]
        ];

        DB::table('tipo_unidade_medida')->insert($items);
    }
}
