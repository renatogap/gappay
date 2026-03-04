<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SegGrupoSeeder extends Seeder
{
    public function run(): void
    {
        $agora = date('Y-m-d H:i:s');
        $items = [
            'usuario_id' => 1,
            'perfil_id' => 2,
            'created_at' => $agora,
            'updated_at' => $agora
        ];

        DB::table('seg_grupo')->insert($items);
    }
}
