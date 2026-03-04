<?php

namespace GapPay\Seguranca\Models\Facade;

use Illuminate\Support\Collection;
use GapPay\Seguranca\Models\Entity\Usuario;
use GapPay\Seguranca\Models\Regras\DB;

class PerfilDB
{
    /**
     * Lista todas as permissões de destaque que um usuário possui
     * Usado na tela de Edição de Perfil para marcar as permissões já concedidas
     * @param int $perfil_id
     * @return Collection
     */
    public static function permissoesDestaqueConcedidas(int $perfil_id): Collection
    {
        return DB::table('seg_permissao as sp')
            ->join('seg_acao as sa', 'sp.acao_id', '=', 'sa.id')
            ->where('perfil_id', $perfil_id)
            ->where('destaque', true)
            ->pluck('sp.acao_id');
    }

    /**
     * Exibe somente as permissões que o usuário possui
     * Perfis root podem ver todas as permissões do sistema
     * Usado no Cadastro/Edição de perfil para montar a tabela de permissões
     * @param Usuario $usuario
     * @return Collection
     */
    public static function permissoesDestaqueConcedidasUsuarioLogado(Usuario $usuario): Collection
    {
        //perfis do usuário logado
        $perfis = UsuarioDB::perfisIDUsuarioLogado($usuario);

        if ($usuario->isRoot($perfis)) {

            $acoes = DB::table('seg_acao as sa')
                ->where('obrigatorio', false)//obrigatórias não devem ficar disponíveis na tela de perfil
                ->where('destaque', true)//Somente ação raiz. Dependências serão calculadas automaticamente
                ->orderBy('grupo')
                ->orderBy('nome_amigavel');
        } else {
            $acoes = DB::table('seg_permissao as sp')
                ->join('seg_acao as sa', 'sp.acao_id', '=', 'sa.id')
                ->whereIn('sp.perfil_id', $perfis)
                ->where('obrigatorio', false)//obrigatórias não devem ficar disponíveis na tela de perfil
                ->where('sa.destaque', true)//Somente ação raiz. Dependências serão calculadas automaticamente
                ->orderBy('grupo')
                ->orderBy('nome_amigavel');
        }

        $campos = [
            'sa.id',
            'sa.nome_amigavel',
            'sa.descricao',
            'sa.grupo',
        ];

        if ($usuario->isRoot(UsuarioDB::perfisIDUsuarioLogado($usuario))) {//para o root irá exibir o total de dependências de cada destaque
            $campos[] = DB::raw("(SELECT count(1) FROM seg_dependencia where acao_atual_id = sa.id) as total_dependencia");
        }

        return $acoes->get($campos);
    }

    /**
     * Todas as ações e dependências concedidas calculadas
     * a partir do array enviado com ids de ações
     * @param array $acoesRaiz
     * @return array
     */
    public static function acoesConcedidas(array $acoesRaiz): array
    {
        if (empty($acoesRaiz))
            return [];

        $acoesNaTela = implode(',', $acoesRaiz);

        $sql = "
            WITH RECURSIVE filho AS (
                select acao_atual_id, acao_dependencia_id
                from seg_dependencia
                where acao_atual_id in ($acoesNaTela)

                UNION

                select sd.acao_atual_id, sd.acao_dependencia_id
                from seg_dependencia as sd
                    join filho on filho.acao_dependencia_id = sd.acao_atual_id
            )
            SELECT acao_dependencia_id as dependencia
            from filho
            group by acao_dependencia_id
            ORDER BY acao_dependencia_id
        ";

        $resultado = DB::select($sql);

        $retorno = [];
        foreach ($resultado as $r) {
            $retorno[] = $r->dependencia;
        }

        return $retorno;
    }
}
