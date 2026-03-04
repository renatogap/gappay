<?php

namespace App\Models\Facade;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use GapPay\Seguranca\Models\Entity\Usuario;
use GapPay\Seguranca\Models\Facade\UsuarioDB;

class SegPerfilDB
{
    public static function grid(\stdClass $p): Collection
    {
        $sql = DB::table('seg_perfil as p')
            ->where('id', '!=', 1)
            ->limit(200)
            ->orderBy('id', 'desc');

        if (isset($p->nome)) {
            $sql->where('nome', 'like', $p->nome);
        }

        return $sql->get([
            'id',
            'nome',
            DB::raw('(select count(1) from seg_grupo where perfil_id = p.id) as total_usuarios')
        ]);
    }

    /**
     * @param Usuario|null $usuarioLogado
     * @return \Illuminate\Support\Collection
     */
    public static function comboPerfil(Usuario $usuarioLogado = null): Collection
    {
        $sql = DB::table('seg_perfil')
            ->orderBy('nome');

        if (!$usuarioLogado->isRoot()) {
            $sql->where('id', '!=', 1);
        }

        return $sql->get([
            'id as value',
            'nome as text'
        ]);
    }

    /**
     * @param int $id
     * @return Collection
     */
    public static function perfilUsuario(int $id): Collection
    {
        return DB::table('seg_grupo')
            ->where('usuario_id', $id)
            ->pluck('perfil_id');
    }
}
