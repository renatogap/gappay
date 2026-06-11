<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CartaoClienteSeeder extends Seeder
{
    public function run(): void
    {
        $agora = date('Y-m-d H:i:s');

        $items = [
            [
                'id' => 1,
                'fk_cartao' => 1,
                'nome' => 'Aimée Pereira - 4˚ano',
                'telefone' => '91980832418',
                'fk_tipo_cliente' => 1, //ALUNO
                'fk_tipo_pagamento' => 6,
                'valor_atual' => 120.00,
                'valor_cartao' => 10.00,
                'devolvido' => 'N',
                'observacao' => 'Crédito cadastro inicial',
                'status' => 2, //EM USO
                'fk_usuario' => 1,
                'created_at' => $agora,
                'responsavel_id' => null
            ],
            [
                'id' => 2,
                'fk_cartao' => 2,
                'nome' => 'William Moraes - 1˚ano A',
                'telefone' => null,
                'fk_tipo_cliente' => 1, //ALUNO
                'fk_tipo_pagamento' => 6,
                'valor_atual' => 100.00,
                'valor_cartao' => 10.00,
                'devolvido' => 'N',
                'observacao' => 'Crédito cadastro inicial',
                'status' => 2, //EM USO
                'fk_usuario' => 999, // usuário genérico
                'created_at' => $agora,
                'responsavel_id' => 1
            ],
            [
                'id' => 3,
                'fk_cartao' => 3,
                'nome' => 'Sophia Anchieta - 2˚ano B',
                'telefone' => null,
                'fk_tipo_cliente' => 1, //ALUNO
                'fk_tipo_pagamento' => 6,
                'valor_atual' => 100.00,
                'valor_cartao' => 10.00,
                'devolvido' => 'N',
                'observacao' => 'Crédito cadastro inicial',
                'status' => 2, //EM USO
                'fk_usuario' => 999, // usuário genérico
                'created_at' => $agora,
                'responsavel_id' => 1
            ],
        ];

        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('cartao_cliente')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        DB::table('cartao_cliente')->insert($items);
    }
}
