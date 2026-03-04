<?php

namespace GapPay\Seguranca\Models\Regras;

use App\Models\Entity\UsuarioTipoCardapio;
use App\Models\Regras\UsuarioEmailRegras;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use GapPay\Seguranca\Models\Entity\Acesso;
use GapPay\Seguranca\Models\Entity\SegGrupo;
use GapPay\Seguranca\Models\Entity\Usuario;
use GapPay\Seguranca\Models\Entity\UsuarioSistema;
use GapPay\Seguranca\Models\Facade\UsuarioDB;

class UsuarioRegras
{

    /**
     * @param Usuario $usuario
     * @return array{nome: string, email: string, rotas: array, menus: array, cadastro_incompleto: bool}
     */
    public static function info(Usuario $usuario): array
    {
        $nomeCompleto = explode(' ', $usuario->nome);
        $primeiroNome = array_shift($nomeCompleto);
        $ultimoNome = array_pop($nomeCompleto);

        $cadastroIncompleto = false;
        if ($usuario->deveTrocarSenha() || $usuario->cadastroIncompleto()) {
            $cadastroIncompleto = true;
        }

        return [
            'nome' => "$primeiroNome $ultimoNome",
            'email' => $usuario->email,
            'rotas' => UsuarioLogado::rotas(),
            'menus' => UsuarioLogado::menus(),
            'cadastro_incompleto' => $cadastroIncompleto,
        ];
    }

    /**
     * Esta função depende fortemente do framework
     * @param int $acesso_id
     * @return void
     */
    public static function logout(int $acesso_id): void
    {
        $oAcesso = Acesso::find($acesso_id);
        $oAcesso->logout = date('Y-m-d H:i:s');
        $oAcesso->fk_sistema_logout = config('policia.codigo');
        $oAcesso->save();

        Auth::logout();
    }

    /**
     * @param UsuarioForm $form
     * @return Usuario
     * @throws \Exception
     */
    public static function editar(UsuarioForm $form): Usuario
    {
        $agora = date('Y-m-d H:i:s');

        /** @var Usuario $usuario */
        $usuario = Usuario::findOrFail($form->id);

        //verifica se existe outro usuário com o mesmo email ou cpf
        if ($usuario->email !== $form->email || $usuario->cpf !== $form->cpf) {

            if($form->cpf) {
                $usu = Usuario::where('cpf', $form->cpf)->where('id', '!=', $usuario->id);
                if ($usu->exists()) {
                    throw new \Exception('Já existe um usuário cadastrado com este CPF.');
                }
            }

            $usu = Usuario::where('email', $form->email)->where('id', '!=', $usuario->id);
            if ($usu->exists()) {
                throw new \Exception('Já existe um usuário cadastrado com este e-mail.');
            }
        }

        $usuario->nome = $form->nome;
        $usuario->email = $form->email;
        $usuario->cpf = $form->cpf ?? null;
        $usuario->nascimento = $form->nascimento ?? null; 
        $usuario->excluido = false;

        if ($form->senha) {
            $usuario->senha = $form->senha;
        }

        $usuario->save();

        //Dá permissão no sistema
//        $usuarioSistema = UsuarioSistema::firstOrCreate([
//            'sistema_id' => config('policia.codigo'),
//            'usuario_id' => $usuario->id,
//        ]);

        // Pesquisa se ja existe um usuario sistema para este usuario e este sistema
        $usuarioSistema = UsuarioSistema::where('sistema_id', config('policia.codigo'))
            ->where('usuario_id', $usuario->id)
            ->first();

        // Obtém o timestamp atual
        $timestamp = microtime(true);

        // Caso nao encontre um usuario sistema, cria um novo
        if (!$usuarioSistema) {
            $usuarioSistema = UsuarioSistema::create([
                'sistema_id' => config('policia.codigo'),
                'usuario_id' => $usuario->id,
                'fk_usuario_cadastro' => Auth::id(),
                'created_at' => $agora
            ]);
        } else {
            $usuarioSistema->fk_usuario_edicao = Auth::id();
            $usuarioSistema->updated_at = date("Y-m-d H:i:s.u", $timestamp);
        }

        //atualiza último acesso do usuário
        $usuarioSistema->status = 1;
        $usuarioSistema->ultimo_acesso = $agora;
        $usuarioSistema->save();

        //removendo todos os perfis já atribuídos que não estão na tela
        SegGrupo::where('usuario_id', $usuario->id)
            ->whereNotIn('perfil_id', $form->perfil)
            ->each(function ($grupo) {
                $grupo->delete();
            });

        //adiciona os perfis que só estão na tela e ainda não foram salvos no banco
        foreach ($form->perfil as $perfil) {

            //foi usando firstOrCreate para não haver erro caso o usuário insira o mesmo perfil novamente
            $segGrupo = SegGrupo::firstOrCreate([//localiza o perfil no banco, caso contrario cria um objeto novo pronto para inserção
                'usuario_id' => $usuario->id,
                'perfil_id' => $perfil
            ]);

            $segGrupo->created_at = $agora;
            $segGrupo->save();//salva elemento novo no banco
        }

        foreach ($form->cardapio as $cardapio) {

            //foi usando firstOrCreate para não haver erro caso o usuário insira o mesmo perfil novamente
            $usuarioCardapio = UsuarioTipoCardapio::firstOrCreate([ //localiza o perfil no banco, caso contrario cria um objeto novo pronto para inserção
                'fk_usuario' => $usuario->id,
                'fk_tipo_cardapio' => $cardapio
            ]);

            $usuarioCardapio->created_at = $agora;
            $usuarioCardapio->save(); //salva elemento novo no banco
        }
        return $usuario;
    }

    /**
     * Cria um usuário no sistema atual
     * Dá permissão no sistema atual
     * Atribui perfis ao usuário
     * Notifica usuário por e-mail
     *
     * @param UsuarioForm $form
     * @return Usuario
     * @throws \Exception
     */
    public static function cadastrar(UsuarioForm $form): Usuario
    {
        $agora = date('Y-m-d H:i:s');

        //verifica se o usuário já possui cadastro em outros sistemas
        if ($usuario = UsuarioDB::localizarUsuarioPorEmailOuCpf($form->email, $form->cpf)) {
            $form->id = $usuario->id;//adiciona o id do usuário e envia para edição
            return self::editar($form);
        }

        //cria usuário
        $usuario = new Usuario();
        $usuario->nome = $form->nome;
        $usuario->email = $form->email;
        $usuario->cpf = $form->cpf ?? null;
        $usuario->nascimento = $form->nascimento ?? null;

        //se houver senha2 então foi cadastrado senha, caso contrário será o cpf e usuário precisará trocar no próximo login
//        $usuario->primeiro_acesso = !!$usuario->senha;
        $usuario->dt_cadastro = $agora;

        if ($form->senha) {
            $usuario->senha = $form->senha;
        } else {
            $usuario->senha = preg_replace('/\D/', '', $usuario->cpf);
        }

        $usuario->save();

        //dá permissão no sistema
        $usuarioSistema = new UsuarioSistema();
        $usuarioSistema->sistema_id = config('policia.codigo');
        $usuarioSistema->usuario_id = $usuario->id;
        $usuarioSistema->ultimo_acesso = $agora;
        $usuarioSistema->created_at = $agora;
        $usuarioSistema->fk_usuario_cadastro = Auth::id();
        $usuarioSistema->status = 1;
        $usuarioSistema->save();

        //atribui perfis ao usuário
        foreach ($form->perfil as $perfil) {
            //foi usando firstOrCreate para não haver erro caso o usuário insira o mesmo perfil novamente
            $segGrupo = SegGrupo::firstOrCreate([//localiza o perfil no banco, caso contrario cria um objeto novo pronto para inserção
                'usuario_id' => $usuario->id,
                'perfil_id' => $perfil
            ]);
            $segGrupo->created_at = $agora;
            $segGrupo->save();//salva elemento novo no banco

        }

        foreach ($form->cardapio as $cardapio) {

            //foi usando firstOrCreate para não haver erro caso o usuário insira o mesmo perfil novamente
            $usuarioCardapio = UsuarioTipoCardapio::firstOrCreate([ //localiza o perfil no banco, caso contrario cria um objeto novo pronto para inserção
                'fk_usuario' => $usuario->id,
                'fk_tipo_cardapio' => $cardapio
            ]);

            $usuarioCardapio->created_at = $agora;
            $usuarioCardapio->save(); //salva elemento novo no banco
        }

        //notifica usuário por e-mail
        UsuarioEmailRegras::emailCadastroUsuario($usuario);
        return $usuario;
    }

    /**
     * @throws \Exception
     */
    public static function atualizarSenha(Usuario $usuario, string $senhaAtual, string $senhaNova): void
    {
        if (!Hash::check($senhaAtual, $usuario->senha2)) {
            throw new \Exception('Senha atual inválida');
        }

        $usuario->primeiro_acesso = false;
        $usuario->senha = $senhaNova;// Attribute para o campo senha atualiza todas as senhas do usuário
        $usuario->save();

        UsuarioEmailRegras::emailAtualizacaoSenha($usuario);
    }

    public static function excluir(Usuario $usuario): void
    {
        SegGrupo::where('usuario_id', $usuario->id)
            ->each(function (SegGrupo $grupo) {
                $grupo->delete();
            });

        UsuarioSistema::where('usuario_id', $usuario->id)
            ->where('sistema_id', config('policia.codigo'))
            ->delete();
    }
}
