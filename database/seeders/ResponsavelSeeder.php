<?php

namespace Database\Seeders;

use DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ResponsavelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $agora = date('Y-m-d H:i:s');
        $items = [
            [
                'id' => '1',
                'nome' => 'RENAN ANCHIETA',
                'email' => 'renangap18@gmail.com',
                'token' => null,
                'concordo' => true,
                'validado' => true,
                'telefone' => '91993039532',
                'senha' => '$2y$12$cdRDtyuv2vSxjTkj1d2sE.I74bk4ReNgKBgtb/fBC/Tz5cGmtcQM6',
                'created_at' => $agora,
                'updated_at' => $agora,
                'deleted_at' => null
            ],
        ];

        DB::table('responsavel')->insert($items);
    }
}
