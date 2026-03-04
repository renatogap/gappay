<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UsuarioSistemaSeeder extends Seeder
{
    public function run(): void
    {
        $agora = date('Y-m-d H:i:s');
        
        $items = [
            'usuario_id' => 1,
            'sistema_id' => 1,
            'ultimo_acesso' => $agora,
            'ativo' => true,
            'status' => true,
            'created_at' => $agora,
            'fk_usuario_cadastro' => 1,
        ];

        DB::table('usuario_sistema')->insert($items);
    }
}
