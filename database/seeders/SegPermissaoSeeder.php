<?php

namespace Database\Seeders;

use GapPay\Seguranca\Models\Entity\SegAcao;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SegPermissaoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $agora = date('Y-m-d H:i:s');
        
        SegAcao::all()->each(function (SegAcao $acao) use ($agora) {
            $items[] = [
                'acao_id' => $acao->id,
                'perfil_id' => 2, // Perfil de Administrador
                'created_at' => $agora
            ];

            DB::table('seg_permissao')->insert($items);
        });

        
    }
}
