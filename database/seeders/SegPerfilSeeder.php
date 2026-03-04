<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SegPerfilSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $agora = date('Y-m-d H:i:s');
        $items = [
            [
                "nome" => 'Root',//Acesso atribuído apenas aos programadores
                "created_at" => $agora,
                "updated_at" => $agora
            ],
            [
                "nome" => 'Administrador',//Faz tudo menos acessar menus do segurança (será atribuído ao dono do sistema)
                "created_at" => $agora,
                "updated_at" => $agora
            ]
        ];

        DB::table('seg_perfil')->insert($items);
    }
}
