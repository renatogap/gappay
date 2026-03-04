<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TipoClienteSeeder extends Seeder
{
    public function run(): void
    {
        $items = [
            ['id' => 1, 'nome' => 'ALUNO', 'created_at' => now()]
        ];

        DB::table('tipo_cliente')->insert($items);
    }
}
