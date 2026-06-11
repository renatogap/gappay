<?php

namespace Database\Seeders;

use Hash;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UsuarioSeeder extends Seeder
{
    public function run(): void
    {
        $agora = date('Y-m-d H:i:s');
        $items = [
            [
                'id' => '1',
                'nome' => 'RENATO PEREIRA',
                'email' => 'renato.19gp@gmail.com',
                'cpf' => '88330796272',
                'nascimento' => null,
                'senha' => null,
                'senha2' => Hash::make('re240987'),
                'created_at' => $agora,
                'updated_at' => $agora
            ],
            [
                'id' => '2',
                'nome' => 'RENAN ANCHIETA',
                'email' => 'renangap18@gmail.com',
                'cpf' => '01143718224',
                'nascimento' => null,
                'senha' => null,
                'senha2' => Hash::make('123456789'),
                'created_at' => $agora,
                'updated_at' => $agora
            ],
            [
                'id' => '999',
                'nome' => 'USUÁRIO GENÉRICO',
                'email' => 'usuariogenerico@email.com',
                'cpf' => '70778637000',
                'nascimento' => null,
                'senha' => null,
                'senha2' => Hash::make('ugenerico321'),
                'created_at' => $agora,
                'updated_at' => $agora
            ],

        ];

        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('usuario')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        DB::table('usuario')->insert($items);
    }
}
