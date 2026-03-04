<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use GapPay\Seguranca\Models\Entity\SegDependencia;

class SegDependenciaSeeder extends Seeder
{
    public function run(): void
    {
        $items = [
            // Configurações
            [
                'acao_atual_id' => 7,
                'acao_dependencia_id' => 36,
            ],
            [
                'acao_atual_id' => 7,
                'acao_dependencia_id' => 37,
            ],
            [
                'acao_atual_id' => 7,
                'acao_dependencia_id' => 38,
            ],
            // Permissões de usuário
            [
                'acao_atual_id' => 10,
                'acao_dependencia_id' => 11,
            ],
            [
                'acao_atual_id' => 10,
                'acao_dependencia_id' => 13,
            ],
            [
                'acao_atual_id' => 12,
                'acao_dependencia_id' => 10,
            ],
            [
                'acao_atual_id' => 12,
                'acao_dependencia_id' => 35,
            ],
            [
                'acao_atual_id' => 12,
                'acao_dependencia_id' => 9,
            ],
            [
                'acao_atual_id' => 12,
                'acao_dependencia_id' => 15,
            ],
            [
                'acao_atual_id' => 14,
                'acao_dependencia_id' => 10,
            ],
            [
                'acao_atual_id' => 14,
                'acao_dependencia_id' => 15,
            ],
            [
                'acao_atual_id' => 14,
                'acao_dependencia_id' => 35,
            ],
            // Permissões de perfil
            [
                'acao_atual_id' => 17,
                'acao_dependencia_id' => 18,
            ],
            [
                'acao_atual_id' => 19,
                'acao_dependencia_id' => 17,
            ],
            [
                'acao_atual_id' => 19,
                'acao_dependencia_id' => 20,
            ],
            [
                'acao_atual_id' => 19,
                'acao_dependencia_id' => 21,
            ],
            [
                'acao_atual_id' => 19,
                'acao_dependencia_id' => 22,
            ],
            [
                'acao_atual_id' => 21,
                'acao_dependencia_id' => 22,
            ],
            [
                'acao_atual_id' => 22,
                'acao_dependencia_id' => 17,
            ],
            [
                'acao_atual_id' => 22,
                'acao_dependencia_id' => 23,
            ],
            [
                'acao_atual_id' => 22,
                'acao_dependencia_id' => 24,
            ],


            // Permissões de ações
            [
                'acao_atual_id' => 26,
                'acao_dependencia_id' => 27,
            ],
            [
                'acao_atual_id' => 28,
                'acao_dependencia_id' => 26,
            ],
            [
                'acao_atual_id' => 31,
                'acao_dependencia_id' => 26,
            ],
            [
                'acao_atual_id' => 28,
                'acao_dependencia_id' => 29,
            ],
            [
                'acao_atual_id' => 28,
                'acao_dependencia_id' => 30,
            ],
            [
                'acao_atual_id' => 31,
                'acao_dependencia_id' => 32,
            ],
            [
                'acao_atual_id' => 31,
                'acao_dependencia_id' => 33,
            ],
        ];

        SegDependencia::insert($items);
    }
}
