<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use GapPay\Seguranca\Models\Entity\SegMenu;

class SegMenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        $items = [
            [
                'acao_id' => 10,
                'pai' => null,
                'nome' => 'Usuários',
                'icone' => '<span class="material-icons" style="font-size: 4em;">lock</span>',
                'dica' => null,
                'ativo' => true,
                'ordem' => 10,
                'configuracoes' => null,
            ],
            [
                'acao_id' => null,
                'pai' => null,
                'nome' => 'Segurança',
                'icone' => null,
                'dica' => null,
                'ativo' => false,
                'ordem' => 11,
                'configuracoes' => '{"icone": "mdi-shield-account"}',
            ],
            [
                'acao_id' => 17,
                'pai' => null,
                'nome' => 'Perfis',
                'icone' => null,
                'dica' => null,
                'ativo' => false,
                'ordem' => 2,
                'configuracoes' => '{"icone": "mdi-menu"}',
            ],
            [
                'acao_id' => 26,
                'pai' => null,
                'nome' => 'Ações',
                'icone' => null,
                'dica' => null,
                'ativo' => false,
                'ordem' => 1,
                'configuracoes' => '{"icone": "mdi-routes"}',
            ],
        ];

        SegMenu::insert($items);
    }
}
