<?php

namespace App\Models\Facade;

use GapPay\Seguranca\Models\Entity\Usuario;
use GapPay\Seguranca\Models\Entity\UsuarioSistema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use GapPay\Seguranca\Models\Facade\UsuarioDB;

class UsuarioLocalDB
{
    public static function grid(\stdClass $p): \Illuminate\Support\Collection
    {
        $codigoSistema = config('policia.codigo');
        $expiracaoLogin = config('policia.expiracao_login');

        $colunas = [
            'u.id',
            'u.nome',
            'u.email',
            DB::raw("GROUP_CONCAT(p.nome ORDER BY p.nome SEPARATOR ', ') as perfil"),
            DB::raw("CASE WHEN TIMESTAMPDIFF(DAY, us.ultimo_acesso, NOW()) > $expiracaoLogin OR us.ativo = 0 THEN 0 ELSE 1 END as ativo"),
        ];

        $sql = DB::table('usuario as u')
            ->join('usuario_sistema as us', 'u.id', '=', 'us.usuario_id')
            ->leftJoin('seg_grupo as gr', 'gr.usuario_id', '=', 'u.id')
            ->leftJoin('seg_perfil as p', 'p.id', '=', 'gr.perfil_id')
            ->where('u.excluido', false)
            ->where('us.sistema_id', $codigoSistema)
            ->groupBy(['u.id', 'u.email', 'u.nome', 'us.ultimo_acesso', 'us.ativo'])
            ->orderBy('u.nome')
            ->limit(200)
            ->select($colunas);

        if (isset($p->usuario) && !UsuarioDB::isRoot($p->usuario)) {
            $sql->where('gr.perfil_id', '!=', 1);
        }

        if(!empty($p->perfil) && is_array($p->perfil)) {
            $sql->whereIn('p.id', $p->perfil);
        }

        if (isset($p->nome)) {
            $sql->where('u.nome', 'like', "%$p->nome%");
        }

        if (isset($p->email)) {
            $sql->where('u.email', $p->email);
        }

        if (isset($p->situacao)) {
            if ($p->situacao !== 'todas') {

                $sql->where('us.status', $p->situacao === 'ativo');
            }
        }

        return $sql->get();
    }

    public static function getUsuarioById($id)
    {
        return DB::table("usuario as u")
            ->where('u.id', $id)
            ->select(['u.*', 'u.unidade as unidade_solicitante'])
            ->first();
    }

    public static function isUsuarioDoSistema($usuarioID)
    {
        return UsuarioSistema::where('usuario_id', $usuarioID)
            ->where('sistema_id', config('parque.codigo'))
            ->count();
    }

    public static function getUsuarioByEmail($email)
    {
        return Usuario::where('email', $email)->where('excluido', false)->first();
    }

    public static function info(string $cpf): ?object
    {
        $usuarioJaCadastrado = UsuarioDB::localizarUsuarioComEmailFuncional($cpf);
        if (!$usuarioJaCadastrado) {
            $usuarioJaCadastrado = UsuarioDB::localizarUsuarioComEmailPessoal($cpf);
        }

        if ($usuarioJaCadastrado) {
            $informacoesCadastro = UsuarioDB::informacoesCadastro($usuarioJaCadastrado);
            $perfilUsuarioLogado = UsuarioDB::perfisIDUsuarioLogado($usuarioJaCadastrado);

            return (object) [
                'usuario' => $usuarioJaCadastrado,
                'perfis' => $perfilUsuarioLogado->pluck('id'),
                'informacoesCadastro' => $informacoesCadastro,
            ];
        }

        return null;
    }

    /**
     * Retorna todos os perfis cadastrados exceto o 1 - Administrador do sistema
     * @param bool $ocultarAdministrador
     * @return
     */
    public static function perfis($ocultarAdministrador = true)
    {
        $sql = DB::table("seg_perfil as p");

        if ($ocultarAdministrador) {
            $sql->where("id", '!=', 1);
        }

        return $sql->get();
    }


    public static function perfilUsuario($id_usuario)
    {
        return DB::table("seg_grupo as g")
            ->where('usuario_id', $id_usuario)
            ->join("seg_perfil as p", 'p.id', '=', 'g.perfil_id')
            ->select('p.id', 'p.nome')
            ->get();
    }
}
