<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UsuarioSeeder extends Seeder
{
    public function run(): void
    {
        $agora = date('Y-m-d H:i:s');
        $items = [
            'id' => '1',
            'nome' => 'RENATO PEREIRA',
            'email' => 'renato.19gp@gmail.com',
            'cpf' => '88330796272',
            'nascimento' => null,
            'senha' => null,
            'senha2' => '$2y$10$N.3I0/jG17stEsRhCC4dDu7TZVi8K1dR.0n11zSnrUJ/3kVeZGF72',
            'created_at' => $agora,
            'updated_at' => $agora
        ];

        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('usuario')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        DB::table('usuario')->insert($items);
    }
}
