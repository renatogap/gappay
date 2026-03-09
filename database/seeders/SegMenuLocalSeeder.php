<?php

namespace Database\Seeders;

use GapPay\Seguranca\Models\Entity\SegMenu;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SegMenuLocalSeeder extends Seeder
{
    public function run(): void
    {
        $hoje = date('Y-m-d H:i:s');

        $items = [
            [
                'id' => 5,
                'acao_id' => 50,
                'pai' => NULL,
                'nome' => 'Cartão',
                'icone' => '<span class="material-icons" style="font-size: 4em;">qr_code</span>',
                'ativo' => 1,
                'ordem' =>  1,
                'created_at' => $hoje
            ],
            [
                'id' => 6,
                'acao_id' => 55,
                'pai' => NULL,
                'nome' => 'Cartão do Aluno',
                'icone' => '<span class="material-icons" style="font-size: 4em;">credit_card</span>',
                'ativo' => 1,
                'ordem' =>  2,
                'created_at' => $hoje
            ],
            [
                'id' => 7,
                'acao_id' => 60,
                'pai' => NULL,
                'nome' => 'Recarga de Crédito',
                'icone' => '<span class="material-icons" style="font-size: 4em;">local_atm</span>',
                'ativo' => 1,
                'ordem' =>  3,
                'created_at' => $hoje
            ],
            [
                'id' => 8,
                'acao_id' => 64,
                'pai' => NULL,
                'nome' => 'Gerenciar Cardápio',
                'icone' => '<span class="material-icons" style="font-size: 4em;">receipt_long</span>',
                'ativo' => 1,
                'ordem' =>  8,
                'created_at' => $hoje
            ],
            [
                'id' => 9,
                'acao_id' => 70,
                'pai' => NULL,
                'nome' => 'Registrar Pedido',
                'icone' => '<span class="material-icons" style="font-size: 4em;">restaurant_menu</span>',
                'ativo' => 1,
                'ordem' =>  5,
                'created_at' => $hoje
            ],
            [
                'id' => 10,
                'acao_id' => 78,
                'pai' => NULL,
                'nome' => 'Meus Pedidos',
                'icone' => '<span class="material-icons" style="font-size: 4em;">deck</span>',
                'ativo' => 0,
                'ordem' =>  6,
                'created_at' => $hoje
            ],
            [
                'id' => 11,
                'acao_id' => 83,
                'pai' => NULL,
                'nome' => 'Monitor de Cozinha',
                'icone' => '<span class="material-icons" style="font-size: 4em;">room_service</span>',
                'ativo' => 0,
                'ordem' =>  7,
                'created_at' => $hoje
            ],
            [
                'id' => 12,
                'acao_id' => 90,
                'pai' => NULL,
                'nome' => 'Relatórios',
                'icone' => '<span class="material-icons" style="font-size: 4em;">print</span>',
                'ativo' => 0,
                'ordem' =>  9,
                'created_at' => $hoje
            ],
            [
                'id' => 13,
                'acao_id' => 96,
                'pai' => NULL,
                'nome' => 'Entradas no Caixa',
                'icone' => '<span class="material-icons" style="font-size: 4em;">print</span>',
                'ativo' => 1,
                'ordem' =>  10,
                'created_at' => $hoje
            ],
            [
                'id' => 14,
                'acao_id' => 91,
                'pai' => NULL,
                'nome' => 'Comissão Promotor',
                'icone' => '<span class="material-icons" style="font-size: 4em;">print</span>',
                'ativo' => 0,
                'ordem' =>  11,
                'created_at' => $hoje
            ],
            [
                'id' => 15,
                'acao_id' => 89,
                'pai' => NULL,
                'nome' => 'Faturamento PDVs por<br>Promotor',
                'icone' => '<span class="material-icons" style="font-size: 4em;">print</span>',
                'ativo' => 0,
                'ordem' =>  12,
                'created_at' => $hoje
            ],
            [
                'id' => 16,
                'acao_id' => 88,
                'pai' => NULL,
                'nome' => 'Faturamento  dos PDVs',
                'icone' => '<span class="material-icons" style="font-size: 4em;">print</span>',
                'ativo' => 0,
                'ordem' =>  13,
                'created_at' => $hoje
            ],
            [
                'id' => 17,
                'acao_id' => 97,
                'pai' => NULL,
                'nome' => 'Pedidos Pendentes',
                'icone' => '<span class="material-icons" style="font-size: 4em;">food_bank</span>',
                'ativo' => 1,
                'ordem' =>  7,
                'created_at' => $hoje
            ],
            [
                'id' => 18,
                'acao_id' => 104,
                'pai' => NULL,
                'nome' => 'Consultar Saldo',
                'icone' => '<span class="material-icons" style="font-size: 4em;">search</span>',
                'ativo' => 1,
                'ordem' =>  8,
                'created_at' => $hoje
            ],
            [
                'id' => 19,
                'acao_id' => 119,
                'pai' => NULL,
                'nome' => 'Devolução de Cartões',
                'icone' => '<span class="material-icons" style="font-size: 4em;">print</span>',
                'ativo' => 0,
                'ordem' =>  9,
                'created_at' => $hoje
            ],
            [
                'id' => 20,
                'acao_id' => 120,
                'pai' => NULL,
                'nome' => 'Cancelamento',
                'icone' => '<span class="material-icons" style="font-size: 4em;">print</span>',
                'ativo' => 0,
                'ordem' =>  14,
                'created_at' => $hoje
            ],
            [
                'id' => 21,
                'acao_id' => 121,
                'pai' => NULL,
                'nome' => 'Transferência de Crédito',
                'icone' => '<span class="material-icons" style="font-size: 4em;">compare_arrows</span>',
                'ativo' => 0,
                'ordem' =>  8,
                'created_at' => $hoje
            ],
            [
                'id' => 22,
                'acao_id' => 128,
                'pai' => NULL,
                'nome' => 'Movimentação do Estoque',
                'icone' => '<span class="material-icons" style="font-size: 4em;">print</span>',
                'ativo' => 0,
                'ordem' =>  15,
                'created_at' => $hoje
            ],
            [
                'id' => 23,
                'acao_id' => 125,
                'pai' => NULL,
                'nome' => 'Gerenciador de Estoque',
                'icone' => '<span class="material-icons" style="font-size: 4em;">post_add</span>',
                'ativo' => 0,
                'ordem' =>  8,
                'created_at' => $hoje
            ],
            [
                'id' => 24,
                'acao_id' => 137,
                'pai' => NULL,
                'nome' => 'Cadastro do Aluno',
                'icone' => '<span class="material-icons" style="font-size: 4em;">account_circle</span>',
                'ativo' => 0,
                'ordem' =>  2,
                'created_at' => $hoje
            ],
            [
                'id' => 25,
                'acao_id' => 146,
                'pai' => NULL,
                'nome' => 'Financeiro',
                'icone' => '<span class="material-icons" style="font-size: 4em;">attach_money</span>',
                'ativo' => 0,
                'ordem' =>  3,
                'created_at' => $hoje
            ],
            [
                'id' => 26,
                'acao_id' => 152,
                'pai' => NULL,
                'nome' => 'Controle Portaria',
                'icone' => '<span class="material-icons" style="font-size: 4em;">login</span>',
                'ativo' => 0,
                'ordem' =>  4,
                'created_at' => $hoje
            ],
            [
                'id' => 27,
                'acao_id' => 153,
                'pai' => NULL,
                'nome' => 'Pagamento dos Alunos',
                'icone' => '<span class="material-icons" style="font-size: 4em;">print</span>',
                'ativo' => 0,
                'ordem' =>  10,
                'created_at' => $hoje
            ],
            [
                'id' => 28,
                'acao_id' => 154,
                'pai' => NULL,
                'nome' => 'Pagamentos em Atraso',
                'icone' => '<span class="material-icons" style="font-size: 4em;">print</span>',
                'ativo' => 0,
                'ordem' =>  10,
                'created_at' => $hoje
            ],
            [
                'id' => 29,
                'acao_id' => 158,
                'pai' => NULL,
                'nome' => 'Vendas',
                'icone' => '<span class="material-icons" style="font-size: 4em;">print</span>',
                'ativo' => 1,
                'ordem' =>  16,
                'created_at' => $hoje
            ],
            [
                'id' => 30,
                'acao_id' => 159,
                'pai' => NULL,
                'nome' => 'Dashboard',
                'icone' => '<span class="material-icons" style="font-size: 4em;">leaderboard</span>',
                'ativo' => 0,
                'ordem' =>  1,
                'created_at' => $hoje
            ]
        ];

        SegMenu::insert($items);
    }
}
