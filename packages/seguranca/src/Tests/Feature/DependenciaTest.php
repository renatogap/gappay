<?php

namespace GapPay\Seguranca\Tests\Feature;

use GapPay\Seguranca\Models\Entity\SegAcao;
use GapPay\Seguranca\Models\Entity\SegDependencia;
use GapPay\Seguranca\Models\Entity\SegPerfil;
use GapPay\Seguranca\Models\Entity\SegPermissao;
use GapPay\Seguranca\Models\Entity\Usuario;
use Tests\TestCase;

class DependenciaTest extends TestCase
{
    /**
     * Na inclusão de nova dependência a trigger deve automanticamente atualizar
     * a tabela de permissão
     *
     * - Cria um perfil fake
     * - Cria uma ação fake
     * - Coloca a ação fake como dependência de /home (ação 1)
     * - Verifica se a trigger colocou permissão para ação fake para o perfil fake
     *
     * @test
     */
    public function inclusao_de_nova_dependencia(): void
    {
        // === Preparando o banco para o teste ===
        /** @var SegPerfil $segPerfil */
        $segPerfil = SegPerfil::factory()->create();

        /** @var SegAcao $segAcaoTela */
        $segAcaoTela = SegAcao::factory()->tela()->create();

        /** @var SegAcao $segAcaoDependenciaTela */
        $segAcaoDependenciaTela = SegAcao::factory()->create();

        //atribuindo permissão na tela ao perfil
        SegPermissao::create([
            'perfil_id' => $segPerfil->id,
            'acao_id' => $segAcaoTela->id,
        ]);

        //Usuário para autenticar no sistema (parte protegida pelo segurança)
        $usuario = Usuario::find(1);

        //formulário usado no teste
        $formulario = [
            'dependencia' => [
                $segAcaoDependenciaTela->id//nova dependência da tela
            ],
            'descricao' => $segAcaoTela->descricao,
            'destaque' => $segAcaoTela->destaque,
            'grupo' => $segAcaoTela->grupo,
            'id' => $segAcaoTela->id,
            'log' => false,
            'metodo' => $segAcaoTela->method,
            'nome' => $segAcaoTela->nome,
            'nome_amigavel' => $segAcaoTela->nome_amigavel,
            'obrigatorio' => false
        ];

        /*
         * O perfil criado já possui acesso à tela
         * Via api será gerado uma nova dependência a esta tela
         * E será testado se a dependência também foi atribuída ao perfil ao fazer atualização via api
         */

        //salvando como dependência usando api
        $resultado = $this->actingAs($usuario)
            ->patchJson("api/acao/$segAcaoTela->id", $formulario);

        //verificando se o servidor confirmou solicitação de atualização
        $resultado->assertStatus(200);

        $segPermissaoNaTela = SegPermissao::where('perfil_id', $segPerfil->id)
            ->where('acao_id', $segAcaoTela->id)
            ->first();

        //assegura que o perfil possui permissão para tela
        $this->assertNotNull($segPermissaoNaTela);

        $segPermissaoNaDependencia = SegPermissao::where('perfil_id', $segPerfil->id)
            ->where('acao_id', $segAcaoDependenciaTela->id)
            ->first();

        //assegura que o perfil possui permissão para dependência da tela
        $this->assertNotNull($segPermissaoNaDependencia);

        //excluindo dados de teste
        SegDependencia::where('acao_atual_id', $segAcaoTela->id)->delete();
        $segPermissaoNaTela->delete();
        $segPermissaoNaDependencia->delete();
        $segAcaoTela->delete();
        $segAcaoDependenciaTela->delete();
        $segPerfil->delete();
    }

    /**
     * Passos do teste
     * Criar perfil fake 1
     * Criar perfil fake 2
     * Criar ação fake 1
     * Criar ação fake 2
     * Colocar ação fake 2 como dependência d ação fake 1
     * Atribuir permissão ao perfil fake 1 (ação fake1 e ação fake2)
     * Atribuir permissão ao perfil fake 2 (ação fake1 e ação fake2)
     * Exluir ação fake2 como dependência de ação fake1
     * Verificar se permissão a ação fake2 foi removida automaticamente de perfil fake1 e perfil fake2
     * @test
     */
    public function exclusao_de_dependencia_de_uma_acao_ja_atribuida_a_perfis_anteriormente()
    {
        /** @var SegPerfil $perfilFake1 */
        $perfilFake1 = SegPerfil::factory()->create();

        /** @var SegPerfil $perfilFake2 */
        $perfilFake2 = SegPerfil::factory()->create();

        /** @var SegAcao $acaoFake1 */
        $acaoFake1 = SegAcao::factory()->tela()->create();

        /** @var SegAcao $acaoFake2 */
        $acaoFake2 = SegAcao::factory()->create();

        /** @var Usuario $usuario */
        $usuario = Usuario::find(1);//usuario root pra autenticar no sistema

        //Incluído acaoFake2 como dependência de acaoFake1
        SegDependencia::create([
            'acao_atual_id' => $acaoFake1->id,
            'acao_dependencia_id' => $acaoFake2->id
        ]);

        // === Perfil fake 1 com permissão a ação fake1 e ação fake2
        /** @var SegPermissao $permissaoPerfil1Acao1 */
        $permissaoPerfil1Acao1 = SegPermissao::create([
            'acao_id' => $acaoFake1->id,
            'perfil_id' => $perfilFake1->id
        ]);

        /** @var SegPermissao $permissaoPerfil1Acao2 */
        SegPermissao::create([
            'acao_id' => $acaoFake2->id,
            'perfil_id' => $perfilFake1->id
        ]);

        // === Perfil fake 2 com permissão a ação fake1 e ação fake2
        /** @var SegPermissao $permissaoPerfil1Acao2 */
        $permissaoPerfil2Acao1 = SegPermissao::create([
            'acao_id' => $acaoFake1->id,
            'perfil_id' => $perfilFake2->id
        ]);

        /** @var SegPermissao $permissaoPerfil1Acao2 */
        SegPermissao::create([
            'acao_id' => $acaoFake2->id,
            'perfil_id' => $perfilFake2->id
        ]);

        /*
         * Excluíndo via api uma dependência e testando se a permissão foi retirada
         * de todos os perfis que a possuiam
         */
        $formulario = [
            'dependencia' => [],
            'descricao' => $acaoFake1->descricao,
            'destaque' => $acaoFake1->destaque,
            'grupo' => $acaoFake1->grupo,
            'id' => $acaoFake1->id,
            'log' => false,
            'metodo' => $acaoFake1->method,
            'nome' => $acaoFake1->nome,
            'nome_amigavel' => $acaoFake1->nome_amigavel,
            'obrigatorio' => false
        ];

        $resultado = $this->actingAs($usuario)
            ->patchJson("api/acao/$acaoFake1->id", $formulario);

        //confirmando resposta da api
        $resultado->assertSuccessful();

        /**
         * Verificando se perfilFake1 perdeu permissão a acaoFake2
         * @var SegPermissao $depedencia
         */
        $depedenciaAcaoFake2Perfil1 = SegPermissao::where('perfil_id', $perfilFake1->id)
            ->where('acao_id', $acaoFake2->id)
            ->exists();

        $this->assertFalse($depedenciaAcaoFake2Perfil1);

        /**
         * Verificando se perfilFake2 perdeu permissão a acaoFake2
         * @var SegPermissao $depedencia
         */
        $depedenciaAcaoFake2Perfil2 = SegPermissao::where('perfil_id', $perfilFake2->id)
            ->where('acao_id', $acaoFake2->id)
            ->exists();
        $this->assertFalse($depedenciaAcaoFake2Perfil2);

        //Removendo o lixo de teste gerado no banco
        $permissaoPerfil2Acao1->delete();
        $permissaoPerfil1Acao1->delete();
        $acaoFake2->delete();
        $acaoFake1->delete();
        $perfilFake2->delete();
        $perfilFake1->delete();
    }

}
