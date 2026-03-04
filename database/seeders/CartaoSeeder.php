<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CartaoSeeder extends Seeder
{
    public function run(): void
    {
        $agora = date('Y-m-d H:i:s');
        
        $items = [
            [
                'id' => 1,
                'codigo' => '480502230226223917',
                'hash' => '54179522db9a147edaf6d0d04ffba8e1',
                'data' => $agora,
                'fk_situacao' => 2,
                'cartao_gerado' => 1,
                'dt_geracao_cartao' => '2026-01-08 10:34:08'
            ],

            [
                'id' => 2,
                'codigo' => '80724211225111926',
                'hash' => '1db1b03c17b566e488b40a1e7ab03a1b',
                'data' => $agora,
                'fk_situacao' => 1,
                'cartao_gerado' => 1,
                'dt_geracao_cartao' => '2026-01-08 10:34:09'
            ],

            [
                'id' => 3,
                'codigo' => '230317211225111926',
                'hash' => 'e0d96a8feb1323722c26e712fb18c272',
                'data' => $agora,
                'fk_situacao' => 1,
                'cartao_gerado' => 1,
                'dt_geracao_cartao' => '2026-01-08 10:34:10'
            ],

            [
                'id' => 4,
                'codigo' => '269166211225111926',
                'hash' => '6eb21d4deeb7daa9bd0c144fcb059fc5',
                'data' => $agora,
                'fk_situacao' => 1,
                'cartao_gerado' => 1,
                'dt_geracao_cartao' => '2026-01-08 10:34:11'
            ],

            [
                'id' => 5,
                'codigo' => '299072211225111926',
                'hash' => '91c2ccb9286f8986bfb688d54a5755af',
                'data' => $agora,
                'fk_situacao' => 1,
                'cartao_gerado' => 1,
                'dt_geracao_cartao' => '2026-01-08 10:34:13'
            ],

            [
                'id' => 6,
                'codigo' => '954452211225111926',
                'hash' => '0131a1f6763d0857691c5c4220f6f217',
                'data' => $agora,
                'fk_situacao' => 1,
                'cartao_gerado' => 1,
                'dt_geracao_cartao' => '2026-01-08 10:34:14'
            ],

            [
                'id' => 7,
                'codigo' => '179884211225111926',
                'hash' => '61edc70931bd5c566b11c1e2d0d1c747',
                'data' => $agora,
                'fk_situacao' => 2,
                'cartao_gerado' => 1,
                'dt_geracao_cartao' => '2026-01-08 10:34:15'
            ],

            [
                'id' => 8,
                'codigo' => '417333211225111926',
                'hash' => '6cbca8568139b4d4426a7edab5746aed',
                'data' => $agora,
                'fk_situacao' => 1,
                'cartao_gerado' => 1,
                'dt_geracao_cartao' => '2026-01-08 10:34:16'
            ],

            [
                'id' => 9,
                'codigo' => '667979211225111926',
                'hash' => '4cd0feb2efd5e3ee16c268e6a87e91fa',
                'data' => $agora,
                'fk_situacao' => 1,
                'cartao_gerado' => 1,
                'dt_geracao_cartao' => '2026-01-08 10:34:18'
            ],

            [
                'id' => 10,
                'codigo' => '713485211225111926',
                'hash' => 'de005cf7c88e940b7f1efa4c679c8886',
                'data' => $agora,
                'fk_situacao' => 1,
                'cartao_gerado' => 1,
                'dt_geracao_cartao' => '2026-01-08 10:34:19'
            ]
        ];

        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('cartao')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        DB::table('cartao')->insert($items);
    }
}
