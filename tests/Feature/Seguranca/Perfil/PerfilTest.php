<?php

namespace Tests\Feature\Seguranca\Perfil;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use PHPUnit\Framework\Attributes\Test;
use GapPay\Seguranca\Models\Entity\SegAcao;
use GapPay\Seguranca\Models\Entity\SegPerfil;
use GapPay\Seguranca\Models\Form\PerfilForm;
use GapPay\Seguranca\Models\Regras\PerfilRegras;
use Tests\TestCase;

class PerfilTest extends TestCase
{
    use DatabaseTransactions;

    #[Test]
    public function validacoes(): void
    {
        $response = $this->actingAs(self::$usuario)->postJson('/api/perfil');
        $response->assertStatus(422);

        $response->assertOnlyJsonValidationErrors([
            'nome',
        ]);
    }

    #[Test]
    public function cadastro(): void
    {
        //criando 3 ações (tela)
        $telas = SegAcao::factory()->tela()->count(3)->create();

        //Preenchendo dados do formulário
        $form = [
            'nome' => 'Perfil de Teste',
            'permissoes' => [
                $telas[0]->id,
                $telas[1]->id,
                $telas[2]->id,
            ],
        ];

        // Criando o perfil
        $response = $this->actingAs(self::$usuario)->postJson('/api/perfil', $form);
        $response->assertStatus(201);

        $response->assertJson([
            'message' => 'Perfil criado com sucesso',
            'perfil' => $response->json('perfil'),
        ]);

        $this->assertDatabaseHas('seg_perfil', [
            'nome' => 'Perfil de Teste',
            'id' => $response->json('perfil')
        ]);

        $this->assertDatabaseHas('seg_permissao', [
            'perfil_id' => $response->json('perfil'),
            'acao_id' => $telas[0]->id,
        ]);
        $this->assertDatabaseHas('seg_permissao', [
            'perfil_id' => $response->json('perfil'),
            'acao_id' => $telas[1]->id,
        ]);
        $this->assertDatabaseHas('seg_permissao', [
            'perfil_id' => $response->json('perfil'),
            'acao_id' => $telas[2]->id,
        ]);
    }

    #[Test]
    public function edicao(): void
    {
        //Criando 3 ações (tela)
        $telas = SegAcao::factory()->tela()->count(3)->create();

        $form = PerfilForm::create([
            'nome' => 'Perfil de Teste',
            'permissoes' => [
                $telas[0]->id,
                $telas[1]->id,
            ],
        ]);

        $perfil = PerfilRegras::cadastrar($form);


        //Preenchendo dados do formulário
        $form = [
            'nome' => 'Perfil de Teste Editado',
            'permissoes' => [
                $telas[0]->id,
//                $telas[1]->id,//removido para o teste detectar a mudança no banco
                $telas[2]->id,//adicionado para o teste detectar a mudança no banco
            ],
        ];

        // Editando o perfil

//        get /api/farofa
//        get /api/farofa/id/edit
//        get /api/farofa/create

//        post /api/farofa
//        put /api/farofa/id
//        delete /api/farofa/id
//        show /api/farofa/id
        $response = $this->actingAs(self::$usuario)->putJson("/api/perfil/$perfil->id", $form);
        $response->assertStatus(200);

        $response->assertJson([
            'message' => 'Perfil atualizado com sucesso',
        ]);

        $this->assertDatabaseHas('seg_perfil', [
            'nome' => 'Perfil de Teste Editado',
            'id' => $perfil->id
        ]);

        $this->assertDatabaseHas('seg_permissao', [
            'perfil_id' => $perfil->id,
            'acao_id' => $telas[0]->id,
        ]);

        $this->assertDatabaseMissing('seg_permissao', [
            'perfil_id' => $perfil->id,
            'acao_id' => $telas[1]->id,
        ]);

        $this->assertDatabaseHas('seg_permissao', [
            'perfil_id' => $perfil->id,
            'acao_id' => $telas[2]->id,
        ]);
    }
}
