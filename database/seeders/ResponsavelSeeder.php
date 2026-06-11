<?php

namespace Database\Seeders;

use DB;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

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
                'senha' => Hash::make('123456789'),
                'created_at' => $agora,
                'updated_at' => $agora,
                'deleted_at' => null
            ],
        ];

        DB::table('responsavel')->insert($items);
    }
}
