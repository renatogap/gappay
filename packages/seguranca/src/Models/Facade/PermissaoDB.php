<?php

namespace GapPay\Seguranca\Models\Facade;

use Illuminate\Support\Facades\DB;

class PermissaoDB
{
    /**
     * @param array $aAcoes
     * @param array $excluir
     * @return array
     */
    public static function dependencias(array $aAcoes, array $excluir = []): array
    {
        $resultado = DB::table('seg_dependencia')
            ->whereIn('acao_atual_id', $aAcoes);

        if (!empty($excluir)) {
            $resultado->whereNotIn('acao_dependencia_id', $excluir);
        }

        $aDependenciaNoBanco = $resultado->pluck('acao_dependencia_id')->toArray();
        $aAcoes = array_unique(array_merge($aAcoes, $aDependenciaNoBanco));

        if (!empty($aDependenciaNoBanco)) {//verifica também as dependências das dependências (se houver)
            return array_merge($aDependenciaNoBanco, self::dependencias($aDependenciaNoBanco, $aAcoes));
        } else {
            return [];
        }
    }
}
