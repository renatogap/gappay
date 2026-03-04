<?php

namespace Database\Seeders;

use App\Models\Entity\Cardapio;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CardapioSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $agora = date('Y-m-d H:i:s');

        $items = [
        
        [
            'id' => 1818, 
            'fk_tipo_cardapio' => '1', 
            'fk_categoria' => 183, 
            'fk_produto' => 2494, 
            'nome_item' => 'Café preto', 
            'detalhe_item' => NULL, 
            'valor' => 3.00, 
            'unid' => 1.000, 
            'status' => 1, 
            'created_at' => $agora, 
            'updated_at' => NULL, 
            'cozinha' => 1, 
            'nome_item_en' => 'Black coffee', 
            'detalhe_item_en' => ''
        ],

        [
            'id' => 1819, 
            'fk_tipo_cardapio' => '1', 
            'fk_categoria' => 183, 
            'fk_produto' => 2495, 
            'nome_item' => 'Café com leite', 
            'detalhe_item' => NULL, 
            'valor' => 5.00, 
            'unid' => 1.000, 
            'status' => 1, 
            'created_at' => $agora, 
            'updated_at' => NULL, 
            'cozinha' => 1, 
            'nome_item_en' => 'Coffee with milk', 
            'detalhe_item_en' => ''
        ],

        [
            'id' => 1820, 
            'fk_tipo_cardapio' => '1', 
            'fk_categoria' => 183, 
            'fk_produto' => 2496, 
            'nome_item' => 'Tapioca c/ manteiga', 
            'detalhe_item' => NULL, 
            'valor' => 6.00, 
            'unid' => 1.000, 
            'status' => 1, 
            'created_at' => $agora, 
            'updated_at' => NULL, 
            'cozinha' => 1, 
            'nome_item_en' => 'Tapioca with butter', 
            'detalhe_item_en' => ''
        ],

        [
            'id' => 1821, 
            'fk_tipo_cardapio' => '1', 
            'fk_categoria' => 183, 
            'fk_produto' => 2497, 
            'nome_item' => 'Tapioca c/ queijo', 
            'detalhe_item' => NULL, 
            'valor' => 7.00, 
            'unid' => 1.000, 
            'status' => 1, 
            'created_at' => $agora, 
            'updated_at' => NULL, 
            'cozinha' => 1, 
            'nome_item_en' => 'Tapioca with cheese', 
            'detalhe_item_en' => ''
        ],

        [
            'id' => 1822, 
            'fk_tipo_cardapio' => '1', 
            'fk_categoria' => 183, 
            'fk_produto' => 2498, 
            'nome_item' => 'Tapioca c/ presunto', 
            'detalhe_item' => NULL, 
            'valor' => 7.00, 
            'unid' => 1.000, 
            'status' => 1, 
            'created_at' => $agora, 
            'updated_at' => NULL, 
            'cozinha' => 1, 
            'nome_item_en' => 'Tapioca with ham', 
            'detalhe_item_en' => ''
        ],

        [
            'id' => 1823, 
            'fk_tipo_cardapio' => '1', 
            'fk_categoria' => 183, 
            'fk_produto' => 2499, 
            'nome_item' => 'Tapioca c/ ovo', 
            'detalhe_item' => NULL, 
            'valor' => 7.00, 
            'unid' => 1.000, 
            'status' => 1, 
            'created_at' => $agora, 
            'updated_at' => NULL, 
            'cozinha' => 1, 
            'nome_item_en' => 'Tapioca with egg', 
            'detalhe_item_en' => ''
        ],

        [
            'id' => 1824, 
            'fk_tipo_cardapio' => '1', 
            'fk_categoria' => 183, 
            'fk_produto' => 2500, 
            'nome_item' => 'Tapioca c/ ovo + queijo + presunto', 
            'detalhe_item' => NULL, 
            'valor' => 11.00, 
            'unid' => 1.000, 
            'status' => 1, 
            'created_at' => $agora, 
            'updated_at' => NULL, 
            'cozinha' => 1, 
            'nome_item_en' => 'Tapioca with egg + cheese + ham', 
            'detalhe_item_en' => ''
        ],

        [
            'id' => 1825, 
            'fk_tipo_cardapio' => '1', 
            'fk_categoria' => 183, 
            'fk_produto' => 2501, 
            'nome_item' => 'Pão completão', 
            'detalhe_item' => NULL, 
            'valor' => 11.00, 
            'unid' => 1.000, 
            'status' => 1, 
            'created_at' => $agora, 
            'updated_at' => NULL, 
            'cozinha' => 1, 
            'nome_item_en' => 'Wholemeal bread', 
            'detalhe_item_en' => ''
        ],

        [
            'id' => 1826, 
            'fk_tipo_cardapio' => '1', 
            'fk_categoria' => 181, 
            'fk_produto' => 2480, 
            'nome_item' => 'Refrigerante lata 350ml', 
            'detalhe_item' => NULL, 
            'valor' => 6.00, 
            'unid' => 1.000, 
            'status' => 1, 
            'created_at' => $agora, 
            'updated_at' => NULL, 
            'cozinha' => 1, 
            'nome_item_en' => 'Soft drink can 350ml', 
            'detalhe_item_en' => ''
        ],

        [
            'id' => 1827, 
            'fk_tipo_cardapio' => '1', 
            'fk_categoria' => 181, 
            'fk_produto' => 2481, 
            'nome_item' => 'Refrigerante G. 200ml', 
            'detalhe_item' => NULL, 
            'valor' => 4.00, 
            'unid' => 1.000, 
            'status' => 1, 
            'created_at' => $agora, 
            'updated_at' => NULL, 
            'cozinha' => 1, 
            'nome_item_en' => 'Soft drink G. 200ml', 
            'detalhe_item_en' => ''
        ],

        [
            'id' => 1828, 
            'fk_tipo_cardapio' => '1', 
            'fk_categoria' => 181, 
            'fk_produto' => 2479, 
            'nome_item' => 'Limoneto H2O', 
            'detalhe_item' => NULL, 
            'valor' => 7.00, 
            'unid' => 1.000, 
            'status' => 1, 
            'created_at' => $agora, 
            'updated_at' => NULL, 
            'cozinha' => 1, 
            'nome_item_en' => 'Limonide H2O', 
            'detalhe_item_en' => ''
        ],

        [
            'id' => 1829, 
            'fk_tipo_cardapio' => '1', 
            'fk_categoria' => 181, 
            'fk_produto' => 2473, 
            'nome_item' => 'Power AD', 
            'detalhe_item' => NULL, 
            'valor' => 10.00, 
            'unid' => 1.000, 
            'status' => 1, 
            'created_at' => $agora, 
            'updated_at' => NULL, 
            'cozinha' => 1, 
            'nome_item_en' => 'Power AD', 
            'detalhe_item_en' => ''
        ],

        [
            'id' => 1830, 
            'fk_tipo_cardapio' => '1', 
            'fk_categoria' => 181, 
            'fk_produto' => 2482, 
            'nome_item' => 'Água 300ml', 
            'detalhe_item' => NULL, 
            'valor' => 2.00, 
            'unid' => 1.000, 
            'status' => 1, 
            'created_at' => $agora, 
            'updated_at' => NULL, 
            'cozinha' => 1, 
            'nome_item_en' => 'Water 300ml', 
            'detalhe_item_en' => ''
        ],

        [
            'id' => 1831, 
            'fk_tipo_cardapio' => '1', 
            'fk_categoria' => 181, 
            'fk_produto' => 2483, 
            'nome_item' => 'Água 500ml', 
            'detalhe_item' => NULL, 
            'valor' => 3.00, 
            'unid' => 1.000, 
            'status' => 1, 
            'created_at' => $agora, 
            'updated_at' => NULL, 
            'cozinha' => 1, 
            'nome_item_en' => 'Water 500ml', 
            'detalhe_item_en' => ''
        ],

        [
            'id' => 1832, 
            'fk_tipo_cardapio' => '1', 
            'fk_categoria' => 181, 
            'fk_produto' => 2487, 
            'nome_item' => 'Água com gás', 
            'detalhe_item' => NULL, 
            'valor' => 4.00, 
            'unid' => 1.000, 
            'status' => 1, 
            'created_at' => $agora, 
            'updated_at' => NULL, 
            'cozinha' => 1, 
            'nome_item_en' => 'Sparkling water', 
            'detalhe_item_en' => ''
        ],

        [
            'id' => 1833, 
            'fk_tipo_cardapio' => '1', 
            'fk_categoria' => 181, 
            'fk_produto' => 2472, 
            'nome_item' => 'Kapo', 
            'detalhe_item' => NULL, 
            'valor' => 5.00, 
            'unid' => 1.000, 
            'status' => 1, 
            'created_at' => $agora, 
            'updated_at' => NULL, 
            'cozinha' => 1, 
            'nome_item_en' => 'Kapo', 
            'detalhe_item_en' => ''
        ],

        [
            'id' => 1834, 
            'fk_tipo_cardapio' => '1', 
            'fk_categoria' => 181, 
            'fk_produto' => 2478, 
            'nome_item' => 'Del Vale', 
            'detalhe_item' => NULL, 
            'valor' => 6.00, 
            'unid' => 1.000, 
            'status' => 1, 
            'created_at' => $agora, 
            'updated_at' => NULL, 
            'cozinha' => 1, 
            'nome_item_en' => 'Del Vale', 
            'detalhe_item_en' => ''
        ],

        [
            'id' => 1835, 
            'fk_tipo_cardapio' => '1', 
            'fk_categoria' => 181, 
            'fk_produto' => 2476, 
            'nome_item' => 'Nescau', 
            'detalhe_item' => NULL, 
            'valor' => 5.00, 
            'unid' => 1.000, 
            'status' => 1, 
            'created_at' => $agora, 
            'updated_at' => NULL, 
            'cozinha' => 1, 
            'nome_item_en' => 'Nescau', 
            'detalhe_item_en' => ''
        ],

        [
            'id' => 1836, 
            'fk_tipo_cardapio' => '1', 
            'fk_categoria' => 181, 
            'fk_produto' => 2477, 
            'nome_item' => 'Pirakids', 
            'detalhe_item' => NULL, 
            'valor' => 4.00, 
            'unid' => 1.000, 
            'status' => 1, 
            'created_at' => $agora, 
            'updated_at' => NULL, 
            'cozinha' => 1, 
            'nome_item_en' => 'Pirakids', 
            'detalhe_item_en' => ''
        ],

        [
            'id' => 1837, 
            'fk_tipo_cardapio' => '1', 
            'fk_categoria' => 184, 
            'fk_produto' => 2502, 
            'nome_item' => 'Lasanha', 
            'detalhe_item' => NULL, 
            'valor' => 20.00, 
            'unid' => 1.000, 
            'status' => 1, 
            'created_at' => $agora, 
            'updated_at' => NULL, 
            'cozinha' => 1, 
            'nome_item_en' => 'Lasagna', 
            'detalhe_item_en' => ''
        ],

        [
            'id' => 1838, 
            'fk_tipo_cardapio' => '1', 
            'fk_categoria' => 184, 
            'fk_produto' => 2503, 
            'nome_item' => 'Strogonoff', 
            'detalhe_item' => NULL, 
            'valor' => 20.00, 
            'unid' => 1.000, 
            'status' => 1, 
            'created_at' => $agora, 
            'updated_at' => NULL, 
            'cozinha' => 1, 
            'nome_item_en' => 'Stroganoff', 
            'detalhe_item_en' => ''
        ],

        [
            'id' => 1839, 
            'fk_tipo_cardapio' => '1', 
            'fk_categoria' => 184, 
            'fk_produto' => 2504, 
            'nome_item' => 'Bife acebolado', 
            'detalhe_item' => NULL, 
            'valor' => 20.00, 
            'unid' => 1.000, 
            'status' => 1, 
            'created_at' => $agora, 
            'updated_at' => NULL, 
            'cozinha' => 1, 
            'nome_item_en' => 'Sirloin steak', 
            'detalhe_item_en' => ''
        ],

        [
            'id' => 1840, 
            'fk_tipo_cardapio' => '1', 
            'fk_categoria' => 184, 
            'fk_produto' => 2505, 
            'nome_item' => 'Chapa mista', 
            'detalhe_item' => NULL, 
            'valor' => 20.00, 
            'unid' => 1.000, 
            'status' => 1, 
            'created_at' => $agora, 
            'updated_at' => NULL, 
            'cozinha' => 1, 
            'nome_item_en' => 'Mixed plate', 
            'detalhe_item_en' => ''
        ],

        [
            'id' => 1841, 
            'fk_tipo_cardapio' => '1', 
            'fk_categoria' => 184, 
            'fk_produto' => 2506, 
            'nome_item' => 'Batata frita', 
            'detalhe_item' => NULL, 
            'valor' => 10.00, 
            'unid' => 1.000, 
            'status' => 1, 
            'created_at' => $agora, 
            'updated_at' => NULL, 
            'cozinha' => 1, 
            'nome_item_en' => 'French fries', 
            'detalhe_item_en' => ''
        ],

        [
            'id' => 1842, 
            'fk_tipo_cardapio' => '1', 
            'fk_categoria' => 180, 
            'fk_produto' => 2486, 
            'nome_item' => 'Sanduíche natural', 
            'detalhe_item' => NULL, 
            'valor' => 10.00, 
            'unid' => 1.000, 
            'status' => 1, 
            'created_at' => $agora, 
            'updated_at' => NULL, 
            'cozinha' => 1, 
            'nome_item_en' => 'Natural sandwich', 
            'detalhe_item_en' => ''
        ],

        [
            'id' => 1843, 
            'fk_tipo_cardapio' => '1', 
            'fk_categoria' => 180, 
            'fk_produto' => 2467, 
            'nome_item' => 'Pizza brotinho', 
            'detalhe_item' => NULL, 
            'valor' => 10.00, 
            'unid' => 1.000, 
            'status' => 1, 
            'created_at' => $agora, 
            'updated_at' => NULL, 
            'cozinha' => 1, 
            'nome_item_en' => 'Pizza brotinho', 
            'detalhe_item_en' => ''
        ],

        [
            'id' => 1844, 
            'fk_tipo_cardapio' => '1', 
            'fk_categoria' => 180, 
            'fk_produto' => 2471, 
            'nome_item' => 'Pastel', 
            'detalhe_item' => NULL, 
            'valor' => 9.00, 
            'unid' => 1.000, 
            'status' => 1, 
            'created_at' => $agora, 
            'updated_at' => NULL, 
            'cozinha' => 1, 
            'nome_item_en' => 'Pastel', 
            'detalhe_item_en' => ''
        ],

        [
            'id' => 1845, 
            'fk_tipo_cardapio' => '1', 
            'fk_categoria' => 180, 
            'fk_produto' => 2466, 
            'nome_item' => 'Salgado', 
            'detalhe_item' => NULL, 
            'valor' => 9.00, 
            'unid' => 1.000, 
            'status' => 1, 
            'created_at' => $agora, 
            'updated_at' => NULL, 
            'cozinha' => 1, 
            'nome_item_en' => 'Salty', 
            'detalhe_item_en' => ''
        ],

        [
            'id' => 1846, 
            'fk_tipo_cardapio' => '1', 
            'fk_categoria' => 180, 
            'fk_produto' => 2468, 
            'nome_item' => 'Lanchão', 
            'detalhe_item' => NULL, 
            'valor' => 9.00, 
            'unid' => 1.000, 
            'status' => 1, 
            'created_at' => $agora, 
            'updated_at' => NULL, 
            'cozinha' => 1, 
            'nome_item_en' => 'Snack bar', 
            'detalhe_item_en' => ''
        ],

        [
            'id' => 1847, 
            'fk_tipo_cardapio' => '1', 
            'fk_categoria' => 180, 
            'fk_produto' => 2484, 
            'nome_item' => 'Croissant', 
            'detalhe_item' => NULL, 
            'valor' => 9.00, 
            'unid' => 1.000, 
            'status' => 1, 
            'created_at' => $agora, 
            'updated_at' => NULL, 
            'cozinha' => 1, 
            'nome_item_en' => 'Croissant', 
            'detalhe_item_en' => ''
        ],

        [
            'id' => 1848, 
            'fk_tipo_cardapio' => '1', 
            'fk_categoria' => 180, 
            'fk_produto' => 2485, 
            'nome_item' => 'Cachorro quente', 
            'detalhe_item' => NULL, 
            'valor' => 8.00, 
            'unid' => 1.000, 
            'status' => 1, 
            'created_at' => $agora, 
            'updated_at' => NULL, 
            'cozinha' => 1, 
            'nome_item_en' => 'Hot dogs', 
            'detalhe_item_en' => ''
        ],

        [
            'id' => 1849, 
            'fk_tipo_cardapio' => '1', 
            'fk_categoria' => 180, 
            'fk_produto' => 2465, 
            'nome_item' => 'Hot dog', 
            'detalhe_item' => NULL, 
            'valor' => 8.00, 
            'unid' => 1.000, 
            'status' => 1, 
            'created_at' => $agora, 
            'updated_at' => NULL, 
            'cozinha' => 1, 
            'nome_item_en' => 'Hot dog', 
            'detalhe_item_en' => ''
        ],

        [
            'id' => 1850, 
            'fk_tipo_cardapio' => '1', 
            'fk_categoria' => 182, 
            'fk_produto' => 2488, 
            'nome_item' => 'Bolo de pote', 
            'detalhe_item' => NULL, 
            'valor' => 10.00, 
            'unid' => 1.000, 
            'status' => 1, 
            'created_at' => $agora, 
            'updated_at' => NULL, 
            'cozinha' => 1, 
            'nome_item_en' => 'Pot cake', 
            'detalhe_item_en' => ''
        ],

        [
            'id' => 1851, 
            'fk_tipo_cardapio' => '1', 
            'fk_categoria' => 182, 
            'fk_produto' => 2489, 
            'nome_item' => 'Salada de fruta', 
            'detalhe_item' => NULL, 
            'valor' => 10.00, 
            'unid' => 1.000, 
            'status' => 1, 
            'created_at' => $agora, 
            'updated_at' => NULL, 
            'cozinha' => 1, 
            'nome_item_en' => 'Fruit salad', 
            'detalhe_item_en' => ''
        ],

        [
            'id' => 1852, 
            'fk_tipo_cardapio' => '1', 
            'fk_categoria' => 182, 
            'fk_produto' => 2490, 
            'nome_item' => 'Pudim', 
            'detalhe_item' => NULL, 
            'valor' => 8.00, 
            'unid' => 1.000, 
            'status' => 1, 
            'created_at' => $agora, 
            'updated_at' => NULL, 
            'cozinha' => 1, 
            'nome_item_en' => 'Pudding', 
            'detalhe_item_en' => ''
        ],

        [
            'id' => 1853, 
            'fk_tipo_cardapio' => '1', 
            'fk_categoria' => 182, 
            'fk_produto' => 2491, 
            'nome_item' => 'Chopp Gourmet', 
            'detalhe_item' => NULL, 
            'valor' => 5.00, 
            'unid' => 1.000, 
            'status' => 1, 
            'created_at' => $agora, 
            'updated_at' => NULL, 
            'cozinha' => 1, 
            'nome_item_en' => 'Chopp Gourmet', 
            'detalhe_item_en' => ''
        ],

        [
            'id' => 1854, 
            'fk_tipo_cardapio' => '1', 
            'fk_categoria' => 182, 
            'fk_produto' => 2492, 
            'nome_item' => 'Brownie', 
            'detalhe_item' => NULL, 
            'valor' => 6.00, 
            'unid' => 1.000, 
            'status' => 1, 
            'created_at' => $agora, 
            'updated_at' => NULL, 
            'cozinha' => 1, 
            'nome_item_en' => 'Brownie', 
            'detalhe_item_en' => ''
        ],

        [
            'id' => 1855, 
            'fk_tipo_cardapio' => '1', 
            'fk_categoria' => 182, 
            'fk_produto' => 2493, 
            'nome_item' => 'Bolo fatia', 
            'detalhe_item' => NULL, 
            'valor' => 5.00, 
            'unid' => 1.000, 
            'status' => 1, 
            'created_at' => $agora, 
            'updated_at' => NULL, 
            'cozinha' => 1, 
            'nome_item_en' => 'Cake slice', 
            'detalhe_item_en' => ''
        ],

        [
            'id' => 1856, 
            'fk_tipo_cardapio' => '1', 
            'fk_categoria' => 180, 
            'fk_produto' => 2464, 
            'nome_item' => 'Pão de queijo', 
            'detalhe_item' => NULL, 
            'valor' => 6.00, 
            'unid' => 1.000, 
            'status' => 1, 
            'created_at' => $agora, 
            'updated_at' => NULL, 
            'cozinha' => 1, 
            'nome_item_en' => 'Cheese bread', 
            'detalhe_item_en' => '']
        ];

        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('cardapio')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        Cardapio::insert($items);
    }
}
