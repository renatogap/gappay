<?php

namespace Database\Seeders;

use App\Models\Entity\Produto;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProdutoSeeder extends Seeder
{
    public function run(): void
    {
        $agora = date('Y-m-d H:i:s');

        $items = [
            [
                'id' => 2464,
                'nome' => 'Pão de queijo',
                'created_at' => $agora,
                'updated_at' => NULL
            ],
            [
                'id' => 2465,
                'nome' => 'Hot dog',
                'created_at' => $agora,
                'updated_at' => NULL
            ],
            [
                'id' => 2466,
                'nome' => 'Salgado',
                'created_at' => $agora,
                'updated_at' => NULL
            ],
            [
                'id' => 2467,
                'nome' => 'Pizza brotinho',
                'created_at' => $agora,
                'updated_at' => NULL
            ],
            [
                'id' => 2468,
                'nome' => 'Lanchão',
                'created_at' => $agora,
                'updated_at' => NULL
            ],
            [
                'id' => 2469,
                'nome' => 'Misto quente',
                'created_at' => $agora,
                'updated_at' => NULL
            ],
            [
                'id' => 2470,
                'nome' => 'Queijo quente',
                'created_at' => $agora,
                'updated_at' => NULL
            ],
            [
                'id' => 2471,
                'nome' => 'Pastel',
                'created_at' => $agora,
                'updated_at' => NULL
            ],
            [
                'id' => 2472,
                'nome' => 'Kapo',
                'created_at' => $agora,
                'updated_at' => NULL
            ],
            [
                'id' => 2473,
                'nome' => 'Power AD',
                'created_at' => $agora,
                'updated_at' => NULL
            ],
            [
                'id' => 2474,
                'nome' => 'Suco',
                'created_at' => $agora,
                'updated_at' => NULL
            ],
            [
                'id' => 2476,
                'nome' => 'Nescau',
                'created_at' => $agora,
                'updated_at' => NULL
            ],
            [
                'id' => 2477,
                'nome' => 'Pirakids',
                'created_at' => $agora,
                'updated_at' => NULL
            ],
            [
                'id' => 2478,
                'nome' => 'Del Vale',
                'created_at' => $agora,
                'updated_at' => NULL
            ],
            [
                'id' => 2479,
                'nome' => 'Limoneto H2O',
                'created_at' => $agora,
                'updated_at' => NULL
            ],
            [
                'id' => 2480,
                'nome' => 'Refrigerante lata 350ml',
                'created_at' => $agora,
                'updated_at' => NULL
            ],
            [
                'id' => 2481,
                'nome' => 'Refrigerante G. 200ml',
                'created_at' => $agora,
                'updated_at' => NULL
            ],
            [
                'id' => 2482,
                'nome' => 'Água 300ml',
                'created_at' => $agora,
                'updated_at' => NULL
            ],
            [
                'id' => 2483,
                'nome' => 'Água 500ml',
                'created_at' => $agora,
                'updated_at' => NULL
            ],
            [
                'id' => 2484,
                'nome' => 'Croissant',
                'created_at' => $agora,
                'updated_at' => NULL
            ],
            [
                'id' => 2485,
                'nome' => 'Cachorro quente',
                'created_at' => $agora,
                'updated_at' => NULL
            ],
            [
                'id' => 2486,
                'nome' => 'Sanduíche natural',
                'created_at' => $agora,
                'updated_at' => NULL
            ],
            [
                'id' => 2487,
                'nome' => 'Água com gás',
                'created_at' => $agora,
                'updated_at' => NULL
            ],
            [
                'id' => 2488,
                'nome' => 'Bolo de pote',
                'created_at' => $agora,
                'updated_at' => NULL
            ],
            [
                'id' => 2489,
                'nome' => 'Salada de fruta',
                'created_at' => $agora,
                'updated_at' => NULL
            ],
            [
                'id' => 2490,
                'nome' => 'Pudim',
                'created_at' => $agora,
                'updated_at' => NULL
            ],
            [
                'id' => 2491,
                'nome' => 'Chopp Gourmet',
                'created_at' => $agora,
                'updated_at' => NULL
            ],
            [
                'id' => 2492,
                'nome' => 'Brownie',
                'created_at' => $agora,
                'updated_at' => NULL
            ],
            [
                'id' => 2493,
                'nome' => 'Bolo fatia',
                'created_at' => $agora,
                'updated_at' => NULL
            ],
            [
                'id' => 2494,
                'nome' => 'Café preto',
                'created_at' => $agora,
                'updated_at' => NULL
            ],
            [
                'id' => 2495,
                'nome' => 'Café com leite',
                'created_at' => $agora,
                'updated_at' => NULL
            ],
            [
                'id' => 2496,
                'nome' => 'Tapioca c/ manteiga',
                'created_at' => $agora,
                'updated_at' => NULL
            ],
            [
                'id' => 2497,
                'nome' => 'Tapioca c/ queijo',
                'created_at' => $agora,
                'updated_at' => NULL
            ],
            [
                'id' => 2498,
                'nome' => 'Tapioca c/ presunto',
                'created_at' => $agora,
                'updated_at' => NULL
            ],
            [
                'id' => 2499,
                'nome' => 'Tapioca c/ ovo',
                'created_at' => $agora,
                'updated_at' => NULL
            ],
            [
                'id' => 2500,
                'nome' => 'Tapioca c/ ovo + queijo + presunto',
                'created_at' => $agora,
                'updated_at' => NULL
            ],
            [
                'id' => 2501,
                'nome' => 'Pão completão',
                'created_at' => $agora,
                'updated_at' => NULL
            ],
            [
                'id' => 2502,
                'nome' => 'Lasanha',
                'created_at' => $agora,
                'updated_at' => NULL
            ],
            [
                'id' => 2503,
                'nome' => 'Strogonoff',
                'created_at' => $agora,
                'updated_at' => NULL
            ],
            [
                'id' => 2504,
                'nome' => 'Bife acebolado',
                'created_at' => $agora,
                'updated_at' => NULL
            ],
            [
                'id' => 2505,
                'nome' => 'Chapa mista',
                'created_at' => $agora,
                'updated_at' => NULL
            ],
            [
                'id' => 2506,
                'nome' => 'Batata frita',
                'created_at' => $agora,
                'updated_at' => NULL
            ]
        ];

        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('produto')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        Produto::insert($items);
    }
}
