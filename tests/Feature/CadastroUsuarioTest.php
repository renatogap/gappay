<?php

namespace Tests\Feature;

use App\Models\Regras\UsuarioLocalRegras;
use Illuminate\Support\Facades\DB;
use PHPUnit\Framework\Attributes\Test;
use GapPay\Seguranca\Models\Entity\LinkTemporario;
use GapPay\Seguranca\Models\Entity\SegGrupo;
use GapPay\Seguranca\Models\Entity\Usuario;
use GapPay\Seguranca\Models\Entity\UsuarioLog;
use GapPay\Seguranca\Models\Entity\UsuarioSistema;
use GapPay\Seguranca\Models\Regras\UsuarioForm;
use Tests\TestCase;

class CadastroUsuarioTest extends TestCase
{
//    use DatabaseTransactions;

//    protected array $connectionsToTransact = [
//        'conexao_padrao',
//        'conexao_seguranca',
//    ];

    private string $cpf = '922.404.300-36';
    private string $cpfNaoCadastrado = '213.695.260-91';

    public function formularioPadrao(array $params = []): array
    {
        return [
            'cpf' => $params['cpf'] ?? $this->cpf,
            'nome' => $params['nome'] ?? fake()->name(),
            'email' => $params['email'] ?? fake()->safeEmail(),
            'senha' => $params['senha'] ?? '123456789',
            'senha_confirmation' => $params['senha_confirmation'] ?? '123456789',
            'perfil' => $params['perfil'] ?? [1],
        ];
    }

    #[Test]
    public function existeUsuario1(): void
    {
        $this->assertNotNull(static::$usuario);
    }

    #[Test]
    public function campos_obrigatorios(): void
    {
        $response = $this->actingAs(static::$usuario)
            ->postJson('/api/admin/usuario');

        // Verifica se os campos abaixo estão presentes na lista de erros
        $response->assertOnlyJsonValidationErrors([
            'cpf',
            'nome',
            'email',
            'perfil',
        ]);
    }

    #[Test]
    public function cpf_invalido(): void
    {
        $this->be(static::$usuario);
        // CPF com todos os números iguais (inválido)
        $response = $this->postJson('/api/admin/usuario', $this->formularioPadrao(['cpf' => '000.000.000-00']));
        $response->assertStatus(422);

        $response->assertInvalid([
            'cpf' => 'CPF inválido',
        ]);

        // CPF com menos de 11 dígitos
        $response = $this->postJson('/api/admin/usuario', $this->formularioPadrao(['cpf' => '111.111.111']));
        $response->assertStatus(422);
        $response->assertInvalid([
            'cpf' => 'CPF inválido',
        ]);

        // CPF inválido
        $response = $this->postJson('/api/admin/usuario', $this->formularioPadrao(['cpf' => '123.456.789-00']));
        $response->assertStatus(422);
        $response->assertInvalid([
            'cpf' => 'CPF inválido',
        ]);
    }

    #[Test]
    public function validacoes_senha(): void
    {
        $dados = $this->formularioPadrao([
            'senha' => '1234567',
            'senha_confirmation' => '1234567',
        ]);

        //testando senha vazia
        $response = $this->actingAs(static::$usuario)
            ->postJson('/api/admin/usuario', $dados);

        $response->assertInvalid([
            'senha' => 'O campo senha deve ter pelo menos 8 caracteres.',
        ]);

        //testando senhas diferentes
        $dados['senha'] = '12345678';
        $response = $this->actingAs(static::$usuario)
            ->postJson('/api/admin/usuario', $dados);
        $response->assertInvalid([
            'senha' => 'A confirmação de senha está incorreta',
        ]);

    }

    #[Test]
    public function cadastro_usuario_novo(): void
    {

        $dados = $this->formularioPadrao();

        $response = $this->actingAs(static::$usuario)
            ->postJson('/api/admin/usuario', $dados);

        $response->assertSuccessful();

        // Verifica se o usuário foi criado
        $this->assertDatabaseHas('usuario', [
            'cpf' => preg_replace('/\D/', '', $this->cpf),
        ]);

        // Verifica se o usuário foi criado com o perfil correto
        $this->assertDatabaseHas('seg_grupo', [
            'usuario_id' => $response->json('usuario'),
            'perfil_id' => 1,
        ]);

        $this->logUsuario($response->json('usuario'));

        // Verifica se apenas o campo depois está preenchido
        $log = UsuarioLog::where('usuario_id', $response->json('usuario'))->latest()->first();

        $this->assertNull($log->antes);
        $this->assertNotNull($log->depois);
    }

    #[Test]
    public function cadastro_de_usuario_que_ja_existe()
    {
        // Verifica se o usuário não existe
        $cpf = preg_replace('/\D/', '', $this->cpf);
        $this->assertDatabaseMissing('usuario', [
            'cpf' => $cpf,
        ]);

        //criando um usuário
        $dados = $this->formularioPadrao();

        $form = UsuarioForm::create($this->formularioPadrao([
            'cpf' => preg_replace('/\D/', '', $this->cpf),
        ]));

        $usuario = UsuarioLocalRegras::criar($form);

        //Cria o mesmo usuário. Deveria apenas atualizar o nome
        $response = $this->actingAs(static::$usuario)
            ->postJson('/api/admin/usuario', [
                'cpf' => $usuario->cpf,
                'nome' => $dados['nome'] . ' atualizado',
                'email' => $usuario->email,
                'senha' => $dados['senha'],
                'senha_confirmation' => $dados['senha_confirmation'],
                'perfil' => [1],
            ])->assertStatus(201);
        $response->assertSuccessful();

        // Verifica se o usuário foi editado
        $this->assertDatabaseHas('usuario', [
            'cpf' => preg_replace('/\D/', '', $this->cpf),
            'nome' => $dados['nome'] . ' atualizado',
        ]);

        $this->logUsuario($usuario->id);
        $log = UsuarioLog::where('usuario_id', $usuario->id)->latest('id')->first();

        // Verifica se o campo antes e depois estão preenchidos
        $this->assertNotNull($log->antes);
        $this->assertNotNull($log->depois);

    }

    #[Test]
    public function exclusao_de_usuario(): void
    {
        //criando um usuário para poder excluí-lo
        $dados = $this->formularioPadrao();
        $response = $this->actingAs(static::$usuario)
            ->postJson('/api/admin/usuario', $dados);

        $response->assertSuccessful();

        //excluindo usuário
        $usuarioCriado = Usuario::where('cpf', preg_replace('/\D/', '', $this->cpf))->first();
        $response = $this->actingAs(static::$usuario)
            ->deleteJson('/api/admin/usuario/' . $usuarioCriado->id);

        $response->assertSuccessful();

        $this->logUsuario($usuarioCriado->id);
//        $this->excluirUsuarioDeTeste($usuarioCriado);
    }

    #[Test]
    public function edicao_usuario(): void
    {
        //criando um usuário para poder editá-lo
        $form = UsuarioForm::create($this->formularioPadrao([
            'cpf' => preg_replace('/\D/', '', $this->cpf),
        ]));
        $usuarioCriado = UsuarioLocalRegras::criar($form);

        //editando usuário
        $dados = $this->formularioPadrao([
            'nome' => 'Nome atualizado',
            'cpf' => static::$usuario->cpf,
            'email' => static::$usuario->email,
            'perfil' => [1],
        ]);
        $response = $this->actingAs(static::$usuario)
            ->putJson('/api/admin/usuario/' . $usuarioCriado->id, $dados);

        $response->assertSuccessful();

        // Verificando se os dados foram gerados nas tabelas
        $this->assertDatabaseHas('usuario', [
            'id' => $usuarioCriado->id,
            'nome' => $dados['nome'],
            'cpf' => preg_replace('/\D/', '', static::$usuario->cpf),
            'email' => static::$usuario->email,
        ]);

        $this->assertDatabaseHas('seg_grupo', [
            'usuario_id' => $usuarioCriado->id,
            'perfil_id' => 1,
        ]);

        $this->logUsuario($usuarioCriado->id);
//        $this->excluirUsuarioDeTeste($usuarioCriado);
    }

    #[Test]
    public function rota_create(): void
    {
        $response = $this->actingAs(static::$usuario)
            ->getJson('/api/admin/usuario/create');
        $response->assertSuccessful();
    }

    #[Test]
    public function rota_informacao_sobre_usuario()
    {
        //rota que retorna informações sobre o usuário (que existe)
        $response = $this->actingAs(static::$usuario)
            ->getJson('/api/admin/usuario/info?cpf=' . static::$usuario->cpf);
        $response->assertStatus(208);

        //rota que retorna informações sobre o usuário (que não existe)
        $response = $this->actingAs(static::$usuario)
            ->getJson('/api/admin/usuario/info?cpf=' . $this->cpfNaoCadastrado);

        $response->assertStatus(204);
    }

    #[Test]
    public function rota_edicao_usuario()
    {
        $response = $this->actingAs(static::$usuario)->getJson('/api/admin/usuario/1/edit');
        $response->assertSuccessful();
    }

    #[Test]
    public function rota_grid()
    {
        $response = $this->actingAs(static::$usuario)->getJson('/api/admin/usuario/grid');
        $response->assertSuccessful();
    }

    #[Test]
    public function atualizarSenha(): void
    {
        $usuario = Usuario::factory()->create([
            'cpf' => preg_replace('/\D/', '', $this->cpf)
        ]);

        /** @var LinkTemporario $linkFactory */
        $linkFactory = LinkTemporario::factory([
            'fk_usuario' => $usuario->id,
        ])->create();

        $response = $this->postJson('/api/atualizar-senha/' . $linkFactory->hash, [
            'senha' => '12345678',
            'senha_confirmation' => '12345678',
        ]);

        $response->assertSuccessful();

        $linkFactory->refresh();
        $this->assertTrue($linkFactory->usado);

    }

    #[Test]
    public function ativar_usuario(): void
    {

        /** @var Usuario $usuarioTeste */
        $usuarioTeste = Usuario::factory()->desabilitado()->create([
            'cpf' => preg_replace('/\D/', '', $this->cpf),
        ]);

        $response = $this->actingAs(static::$usuario)
            ->putJson("/api/admin/usuario/modificar-situacao/$usuarioTeste->id/1");


        $response->assertSuccessful();

        $this->assertDatabaseHas('usuario_sistema', [
            'usuario_id' => $usuarioTeste->id,
            'status' => 1,
        ]);
    }

    #[Test]
    public function desativar_usuario()
    {
        /** @var Usuario $usuarioTeste */
        $usuarioTeste = Usuario::factory()->habilitado()->create([
            'cpf' => preg_replace('/\D/', '', $this->cpf),
        ]);

        $response = $this->actingAs(static::$usuario)
            ->putJson("/api/admin/usuario/modificar-situacao/$usuarioTeste->id/0");
        $response->assertSuccessful();

        $this->assertDatabaseHas('usuario_sistema', [
            'usuario_id' => $usuarioTeste->id,
            'status' => 0,
        ]);
    }

    public function logUsuario(string $usuario_id): void
    {
        // Valida log
        $this->assertDatabaseHas('usuario_log', [
            'usuario_id' => $usuario_id,
            'responsavel' => static::$usuario->id,
            'sistema_id' => config('policia.codigo'),
            'ip' => implode(', ', request()->ips())
        ]);
    }

    protected function tearDown(): void
    {
        $usuario = Usuario::where('cpf', preg_replace('/\D/', '', $this->cpf))->first();

        if ($usuario) {
//            SegGrupo::where('usuario_id', $usuario->id)->delete();
//            UsuarioSistema::where('usuario_id', $usuario->id)->delete();
//            LinkTemporario::where('fk_usuario', $usuario->id)->delete();
//            UsuarioLog::where('usuario_id', $usuario->id)->delete();
//            DB::select('DELETE FROM usuario WHERE id = ?', [$usuario->id]);
        }

        parent::tearDown();
    }

}
