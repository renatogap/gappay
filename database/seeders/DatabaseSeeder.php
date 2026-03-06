<?php

namespace Database\Seeders;

use App\Models\Entity\Cardapio;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        $this->call([
            SistemaSeeder::class,
            UsuarioSeeder::class,
            UsuarioSistemaSeeder::class,
            SegPerfilSeeder::class,
            SegGrupoSeeder::class,
            SegAcaoSeeder::class,
            SegAcaoLocalSeeder::class,
            SegDependenciaSeeder::class,
            SegPermissaoSeeder::class,
            SegMenuSeeder::class,
            SegMenuLocalSeeder::class,
            ProdutoSeeder::class,
            SituacaoCartaoSeeder::class,
            CartaoSeeder::class,
            CardapioTipoSeeder::class,
            CardapioCategoriaSeeder::class,
            CardapioSeeder::class,
            // CardapioFotoSeeder::class,
            SituacaoPedidoSeeder::class,
            TipoClienteSeeder::class,
            TipoPagamentoSeeder::class,
            TipoUnidadeMedidaSeeder::class,
            CartaoClienteSeeder::class
        ]);
    }
}
