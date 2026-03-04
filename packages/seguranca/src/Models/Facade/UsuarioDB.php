<?php

namespace GapPay\Seguranca\Models\Facade;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use GapPay\Seguranca\Models\Entity\Acesso;
use GapPay\Seguranca\Models\Entity\SegGrupo;
use GapPay\Seguranca\Models\Entity\Usuario;
use GapPay\Seguranca\Models\Entity\UsuarioSistema;
use Ramsey\Uuid\Uuid;

class UsuarioDB
{
    public static function dadosSRH($cpf)
    {
        $cpf = preg_replace('/\D/', '', $cpf);
        return DB::table('srh.sig_servidor as ss')
            ->where('cpf', $cpf)
            ->first([
                'cpf',
                'nome',
                'email',
                'dt_nascimento',
            ]);
    }

    public static function grid(\stdClass $p, Usuario $usuario): Collection
    {
        $codigoSistema = config('policia.codigo');
        $expiracaoLogin = config('policia.expiracao_login');

        $colunas = [
            'u.id',
            'u.nome',
            'u.email',
            DB::raw("CASE WHEN current_date - us.ultimo_acesso > $expiracaoLogin THEN 0 ELSE 1 END as ativo"),
        ];
        $sql = DB::table('usuario as u')
            ->join('usuario_sistema as us', 'u.id', '=', 'us.usuario_id')
            ->where('u.excluido', false)
            ->where('us.sistema_id', $codigoSistema)
            ->limit(200);


        //se não for root oculta todos os usuários root da consulta (root é perfil_id = 1)
        if (!$usuario->isRoot()) {

            $sql->join('seg_grupo as gr', 'gr.usuario_id', '=', 'us.usuario_id')
                ->where(function ($s) {
                    $s->where('gr.perfil_id', 1)
                        ->orWhereNull('gr.perfil_id');
                });
//                ->where('u.id', '!=', 1)
//                ->where('us.sistema_id', $codigoSistema);
        }

        if (isset($p->nome)) {
            $sql->where('u.nome', 'like', "%$p->nome%");
        }
        if (isset($p->email)) {
            $sql->where('u.email', 'like', "%$p->email%");
        }

        return $sql->get($colunas);
    }

    /**
     * Coleção de perfis do usuário logado
     * @param Usuario $usuario
     * @return Collection
     */
    public static function perfisIDUsuarioLogado(Usuario $usuario): Collection
    {
        //Gera cache dos perfis do usuário. 7200 segundos = 2 horas
//        return Cache::remember("usuario$usuario->id", 7200, function () use ($usuario) {
        $sql = SegGrupo::where('usuario_id', $usuario->id)
            ->get('perfil_id as id');

        return $sql;
//        });
    }

    /**
     * @param int $id
     * @return Usuario
     */
    public static function edicao(int $id): Usuario
    {
        return Usuario::select([
            'id',
            'nome',
            'cpf',
            'email',
            'nascimento',
        ])
            ->find($id);
    }

    /**
     * @param string|null $email
     * @param string|null $cpf
     * @return Usuario|null
     */
    public static function localizarUsuarioPorEmailOuCpf(?string $email, ?string $cpf): ?Usuario
    {
        if ($cpf) {
            $cpf = preg_replace('/\D/', '', $cpf);
            return Usuario::where('cpf', $cpf)
                ->orderBy('id', 'desc')
                ->first();
        }

        //Pesquisa primeiro por e-mail. (maior importância, pois não pode repetir)
        $usuario = Usuario::where('email', $email)
            ->first();

        return $usuario ?? null;
    }

    /**
     * Verifica se o usuário possui perfil de root (somente desenvolvedores)
     * perfil_id = 1 (root) ou se é o usuário Root (id = 1)
     * @param Usuario $usuario
     * @return bool
     */
    public static function isRoot(Usuario $usuario): bool
    {
        return $usuario->isRoot(self::perfisIDUsuarioLogado($usuario));
    }

    public static function validarAcesso(string $email, string $senha): ?Usuario
    {
        $usuario = Usuario::where('email', $email)
            ->where('excluido', false)
            ->first();

        if (!$usuario || !Hash::check($senha, $usuario->senha2)) {
            return null;
        }

        return $usuario;
    }

    /**
     * Renova o acesso do usuário atribuindo a data de hoje à tabela usuario_sistema
     * @param Usuario $usuario
     * @return void
     */
    public static function renovarAcesso(Usuario $usuario): void
    {
        if ($usuario->id === 1) {
            return;
        }

        $sistema_id = config('policia.codigo');
        $oUsuarioSistema = UsuarioSistema::where('usuario_id', $usuario->id)
            ->where('sistema_id', $sistema_id)
            ->first();
        $oUsuarioSistema->ultimo_acesso = date('Y-m-d');
        $oUsuarioSistema->save();
    }

    /**
     * Registra o login do usuário na tabela acesso
     * @param Usuario $usuario
     * @param string|null $ip
     * @param string|null $agente
     * @return void
     */
    public static function registrarLogin(Usuario $usuario, ?string $ip, ?string $agente): void
    {
        $oAcesso = Acesso::create([
            'fk_usuario' => $usuario->id,
            'ip' => $ip,
            'login' => date('Y-m-d H:i:s'),
            'user_agent' => $agente,
            'session_id' => Uuid::uuid4(),
            'fk_sistema_login' => config('policia.codigo')
        ]);

        Session::put('acesso_id', $oAcesso->id);
    }

    public static function localizarUsuarioPorCpf(string $cpf): Builder
    {
        $cpf = preg_replace('/\D/', '', $cpf);
        return Usuario::where('cpf', $cpf)
            ->select([
                'id',
                'nome',
                'email',
            ]);
    }

    public static function localizarUsuarioComEmailFuncional(string $cpf): Usuario|null
    {
        return self::localizarUsuarioPorCpf($cpf)
            ->where('email', 'like', '%@policiacivil.pa.gov.br')
            ->first();
    }

    public static function localizarUsuarioComEmailPessoal(string $cpf): Usuario|null
    {
        return self::localizarUsuarioPorCpf($cpf)
            ->where('email', 'not like', '%@policiacivil.pa.gov.br')
            ->first();
    }

    public static function informacoesCadastro(Usuario $usuario): ?UsuarioSistema
    {
        return UsuarioSistema::where('usuario_id', $usuario->id)
            ->leftJoin('usuario as uc', 'usuario_sistema.fk_usuario_cadastro', '=', 'uc.id')
            ->leftJoin('usuario as ua', 'usuario_sistema.fk_usuario_edicao', '=', 'ua.id')
            ->where('sistema_id', config('policia.codigo'))
            ->first([
                'sistema_id',
                'uc.nome as usuario_cadastro',
                DB::raw("DATE_FORMAT(usuario_sistema.created_at, '%d/%m/%Y %H:%i:%S') as data_cadastro"),
                'ua.nome as usuario_edicao',
                DB::raw("DATE_FORMAT(usuario_sistema.updated_at, '%d/%m/%Y %H:%i:%S') as data_edicao"),
            ]);
    }
}
