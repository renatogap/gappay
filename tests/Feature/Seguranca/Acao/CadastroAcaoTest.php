<?php

namespace Tests\Feature\Seguranca\Acao;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use PHPUnit\Framework\Attributes\Test;
use GapPay\Seguranca\Models\Entity\SegAcao;
use GapPay\Seguranca\Models\Entity\SegDependencia;
use GapPay\Seguranca\Models\Entity\SegPerfil;
use GapPay\Seguranca\Models\Entity\SegPermissao;
use GapPay\Seguranca\Models\Form\AcaoForm;
use GapPay\Seguranca\Models\Regras\AcaoRegras;
use Tests\TestCase;

class CadastroAcaoTest extends TestCase
{
    use DatabaseTransactions;

    private string $url = 'api/acao';
    public function formularioPadrao(array $params = []): array
    {
        return [
            'nome' => $params['nome'] ?? fake()->url(),
            'method' => $params['method'] ?? 'GET',
            'descricao' => $params['descricao'] ?? fake()->sentence(),
            'destaque' => $params['destaque'] ?? false,
            'nome_amigavel' => $params['nome_amigavel'] ?? fake()->userName(),
            'grupo' => $params['grupo'] ?? 'GeradoAutomaticamente',
            'obrigatorio' => $params['obrigatorio'] ?? false,
            'log_acesso' => $params['log_acesso'] ?? false,
            'dependencia' => $params['dependencia'] ?? [],
        ];
    }

    #[Test]
    public function campos_obrigatorios(): void
    {
        $response = $this->actingAs(static::$usuario)
            ->postJson($this->url);

        $response->assertStatus(422);

        // Verifica se os campos abaixo estão presentes na lista de erros
        $response->assertJsonValidationErrors([
            'nome',
        ]);

    }

    #[Test]
    public function nome_amigavel_obrigatorio(): void
    {
        $formulario = $this->formularioPadrao([
            'destaque' => true,// nome_amigavel é obrigatório quando destaque é true
            'nome_amigavel' => '',// nome_amigavel não foi informado
        ]);

        $response = $this->actingAs(static::$usuario)
            ->postJson($this->url, $formulario);

        $response->assertStatus(422);

        $response->assertJsonValidationErrors([
            'nome_amigavel',
        ]);
    }

    #[Test]
    public function grid(): void
    {
        $response = $this->actingAs(static::$usuario)
            ->getJson('api/acao/grid');

        $response->assertStatus(200);
    }

    #[Test]
    public function cadastro_acao(): void
    {
        $formulario = $this->formularioPadrao();

        $response = $this->actingAs(static::$usuario)
            ->postJson($this->url, $formulario)
            ->assertStatus(201);

        // Verifica se a ação foi cadastrada
        $acao = $response->json('acao');
        $this->assertNotNull(SegAcao::find($acao));

        // Exclui a ação
        SegAcao::find($acao)->delete();
    }

    #[Test]
    public function cadastro_com_dependencia(): void
    {
        $formulario = $this->formularioPadrao([
            'dependencia' => [3, 4, 5],
        ]);

        $response = $this->actingAs(static::$usuario)
            ->postJson($this->url, $formulario);
        $response->assertStatus(201);

        // Verifica se a ação foi cadastrada
        $this->assertDatabaseHas(SegAcao::class, [
            'id' => $response->json('acao')
        ]);

        /** @var Collection $depencias */
        $depencias = SegDependencia::where('acao_atual_id', $response->json('acao'))
            ->whereIn('acao_dependencia_id', [3, 4, 5])
            ->get();
        $this->assertCount(3, $depencias);
    }

    /**
     * Cadastro de rota repetida (mesmo nome e mesmo método)
     */
    #[Test]
    public function rota_repetida()
    {
        SegAcao::factory()->create([
            'nome' => 'teste'
        ]);

        $formulario = $this->formularioPadrao([
            'nome' => 'teste'
        ]);

        $response = $this->actingAs(static::$usuario)
            ->postJson($this->url, $formulario);

        $response->assertStatus(422);

        $response->assertOnlyJsonValidationErrors([
            'nome' => 'Rota já está cadastrada'
        ]);
    }

    /**
     * Cadastrar um perfil
     *
     * Cadastrar uma ação (tela)
     * Dar permissão a esta ação para o perfil
     * Verificar se a ação está na lista de ações do perfil 1 e 2
     * Alterar dependências da ação
     * Verificar se as dependências foram adicionadas ao perfil 1 e 2
     * @return void
     */
    #[Test]
    public function alteracao_de_dependencias_de_acao()
    {
        //cria um perfil e um usuário
        $perfil = SegPerfil::factory()->create(['nome' => 'Perfil de Teste']);

        //cria uma ação de teste e uma ação dependência
        $chuckNorris = SegAcao::factory()->create(['nome' => 'Chuck Norris (não depende de ninguém)']);

        $nutella = SegAcao::factory()->tela()->make(['nome' => 'Ação nutella (depende de Chuck Norris)']);
        $nutella = AcaoRegras::criar(AcaoForm::create([
            'nome' => $nutella->nome,
            'method' => $nutella->method,
            'descricao' => $nutella->descricao,
            'destaque' => $nutella->destaque,
            'nome_amigavel' => $nutella->nome_amigavel,
            'grupo' => $nutella->grupo,
            'obrigatorio' => $nutella->obrigatorio,
            'log_acesso' => $nutella->log_acesso,
            'dependencia' => [$chuckNorris->id],
        ]));

        $this->assertDatabaseHas('seg_dependencia', [
            'acao_atual_id' => $nutella->id,
            'acao_dependencia_id' => $chuckNorris->id
        ]);

        // adiciona permissão para o perfil
        SegPermissao::create([
            'acao_id' => $nutella->id,
            'perfil_id' => $perfil->id
        ]);
        SegPermissao::create([
            'acao_id' => $chuckNorris->id,
            'perfil_id' => $perfil->id
        ]);
        $this->assertDatabaseHas('seg_permissao', [
            'acao_id' => $nutella->id,
            'perfil_id' => $perfil->id
        ]);
        $this->assertDatabaseHas('seg_permissao', [
            'acao_id' => $chuckNorris->id,
            'perfil_id' => $perfil->id
        ]);

        /*
         * === Preparando a edição da ação ===
         *
         *
         */

        // Cria uma nova ação para adicionar como dependência
        $bruceLee = SegAcao::factory()->create(['nome' => 'Bruce Lee (não depende de ninguém)']);

        // usa a rota de edição de ação para adicionar a dependência criada acima
        $response = $this->actingAs(self::$usuario)->putJson("{$this->url}/$nutella->id", [
            'nome' => $nutella->nome,
            'nome_amigavel' => $nutella->nome_amigavel,
            'dependencia' => [$chuckNorris->id, $bruceLee->id],
            'method' => $nutella->method,
            'descricao' => $nutella->descricao,
            'destaque' => $nutella->destaque,
            'obrigatorio' => $nutella->obrigatorio,
            'grupo' => $nutella->grupo,
            'log_acesso' => $nutella->log_acesso,
        ]);

        $response->assertStatus(200);

        $this->assertDatabaseHas('seg_dependencia', [
            'acao_atual_id' => $nutella->id,
            'acao_dependencia_id' => $chuckNorris->id
        ]);

        $this->assertDatabaseHas('seg_dependencia', [
            'acao_atual_id' => $nutella->id,
            'acao_dependencia_id' => $bruceLee->id
        ]);

        //verifica se o perfil tem acesso à ação dependência
        $this->assertDatabaseHas('seg_permissao', [
            'acao_id' => $nutella->id,
            'perfil_id' => $perfil->id
        ]);
        $this->assertDatabaseHas('seg_permissao', [
            'acao_id' => $chuckNorris->id,
            'perfil_id' => $perfil->id
        ]);

        $this->assertDatabaseHas('seg_permissao', [
            'acao_id' => $bruceLee->id,
            'perfil_id' => $perfil->id
        ]);
    }

}
