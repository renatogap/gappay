<?php

namespace GapPay\Seguranca\Models\Regras;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use GapPay\Seguranca\Models\Entity\SegGrupo;
use GapPay\Seguranca\Models\Facade\UsuarioDB;

class UsuarioLogado
{
    /**
     * Lista de id de perfil ou Builder para usar em outro sql
     * @param bool $executar
     * @return Collection|Builder
     */
    public static function perfisID(bool $executar = false): Collection|Builder
    {
        $sql = SegGrupo::where('usuario_id', Auth::id())
            ->select('perfil_id');
        return $executar ? $sql->get() : $sql;
    }

//    /**
//     * Verifica se o usuário logado pode acessar uma determinada url
//     * @param string $url
//     * @param string $method
//     * @return bool
//     */
//    public static function permissaoUrl(string $url, string $method): bool
//    {
//        return DB::table('seg_permissao as sp')
//            ->join('seg_acao as sa', 'sa.id', '=', 'sp.acao_id')
//            ->where(function ($sql) use ($url, $method) {
//                $sql->where('sa.nome', $url);
//                $sql->where('sa.method', 'like', $method);
//                $sql->whereIn('sp.perfil_id', self::perfisID());
//            })
//            ->orWhere('sa.obrigatorio', true)
//            ->exists();
//    }
    /**
     * Verifica se o usuário logado pode acessar uma determinada url
     * @param AcaoSolicitada $acao
     * @return bool
     */
    public static function permissaoUrl(AcaoSolicitada $acao): bool
    {
        //Para fins de desenvolvimento/testes o Controle de acesso pode ser desativado
        if (isControleAcessoAtivo() === false) {
            return true;
        }

        if ($acao->getAcao()?->obrigatorio) {
            return true;
        }

        if (UsuarioDB::isRoot(Auth::user())) {
            return true;
        }

        return DB::table('seg_permissao as sp')
            ->whereIn('sp.perfil_id', self::perfisID())
            ->where('sp.acao_id', $acao->getAcao()->id)
            ->exists();
    }

    /**
     * Todas as rotas que o usuário pode acessar
     * @return Collection|array
     */
    public static function rotas(): Collection|array
    {
        $perfis = UsuarioDB::perfisIDUsuarioLogado(Auth::user());

        if (Auth::id() === 1) {//usuário Root sempre tem acesso a tudo
            return ['*'];
        }

        if ($perfis->contains('id', 1)) {//usuário com perfil 1 (root)
            return ['*'];
        }

        $sql = DB::table('seg_acao as sa')
            ->leftJoin('seg_permissao as sp', 'sa.id', '=', 'sp.acao_id')
            ->whereIn('sp.perfil_id', $perfis)
            ->orWhere('sa.obrigatorio', true)
            ->orderBy('sa.nome');

        return $sql->pluck('nome');
    }

    /**
     * O menu raiz deve ter acao_id = null e pai = null
     * @return Collection
     */
    public static function menus(): Collection
    {
        $usuario = Auth::user();
        $perfisUsuario = UsuarioDB::perfisIDUsuarioLogado($usuario);

        $colunas = [
            'id',
            'nome',
            'pai',
            'ordem',
            'configuracoes',
            'acao',
            'icone'
        ];

        $sql = DB::table('vw_menu')
            ->select($colunas)
            ->where('ativo', true)
            ->orderBy('ordem')
            ->orderBy('nome')
            ->groupBy($colunas);

        //Usuário root não entra neste filtro
        if (!UsuarioDB::isRoot($usuario)) {//Para quem não é root

            if (!$perfisUsuario->isEmpty()) {//exibe somente itens que o usuário possui permissão

                $sql->where(function ($sql) use ($perfisUsuario) {
                    //WHERE perfil_id in (1,2,3) OR obrigatorio = true OR (pai IS NULL AND acao IS NULL)
                    $sql->whereIn('perfil_id', $perfisUsuario)
                        ->orWhere('obrigatorio', true)
                        ->orWhere(function ($subsql) {
                            $subsql->whereNull('pai')
                                ->whereNull('acao');
                        });//traz o item raiz também
                });
            } else {//usuário sem perfil (somente itens obrigatórios)
                $sql->where('obrigatorio', true);
            }
        }

        $resultado = $sql->get();

        $menu = collect(static::montarEstruturaMenu($resultado));

        //remove itens que não possuem pai e ação (trazidos pela vw_menu)
        return $menu->reject(function ($item) {
            return $item->pai === null && $item->acao === null && !isset($item->submenus);
        })->values();
    }

    public static function montarEstruturaMenu($itens, $pai = null): array
    {
        $retorno = [];
        foreach ($itens as $item) {
            if ($item->pai === $pai) {//é um item raiz
                $filhos = static::montarEstruturaMenu($itens, $item->id);
                if ($filhos) {
                    $item->submenus = $filhos;
                }
                $retorno[] = $item;
            }
        }
        return $retorno;
    }
}
