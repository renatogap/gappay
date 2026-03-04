<?php

namespace Database\Seeders;

use App\Models\Entity\SituacaoCartao;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SituacaoCartaoSeeder extends Seeder
{
    public function run(): void
    {
        $items = [
            ['id' => 1, 'nome' => 'DISPONÍVEL NO CAIXA', 'created_at' => now()],
            ['id' => 2, 'nome' => 'EM USO', 'created_at' => now()],
            ['id' => 3, 'nome' => 'BLOQUEADO', 'created_at' => now()]
        ];

        SituacaoCartao::insert($items);
    }
}
