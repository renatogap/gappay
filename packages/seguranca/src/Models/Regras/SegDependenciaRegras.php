<?php

namespace GapPay\Seguranca\Models\Regras;

use GapPay\Seguranca\Models\Entity\SegAcao;
use GapPay\Seguranca\Models\Entity\SegDependencia;
use GapPay\Seguranca\Models\Facade\SegAcaoDB;

class SegDependenciaRegras
{
    /**
     * Atualiza todas as dependências de uma ação
     * @param string $acaoPrincipal
     * @param int $acaoDependencia
     * @return void
     */
    public static function cadastrar(string $acaoPrincipal, int $acaoDependencia)
    {
        $oAcao = SegAcao::where('nome', $acaoPrincipal)
            ->select('id')
            ->first();

        SegDependencia::create([
            'acao_atual_id' => $oAcao->id,
            'acao_dependencia_id' => $acaoDependencia
        ]);
    }

    public static function dependenciaJaCadastrada(int $acaoSolicitadaID, int $telaID): bool
    {
        return DB::table('seg_dependencia as sd')
            ->join('seg_acao as sa', 'sa.id', '=', 'sd.acao_atual_id')
            ->where('acao_atual_id', $telaID)
            ->where('acao_dependencia_id', $acaoSolicitadaID)
            ->exists();
    }

//    /**
//     * Verifica se a ação solicitada já está como dependência da tela
//     * @param string $url
//     * @return bool
//     */
//    public static function dependenciaJaCadastrada(): bool
//    {
//        $acaoSolicitada = AcaoSolicitada::getInstance();
//        $telaID = SegAcaoDB::id($acaoSolicitada->getTelaSolicitante(), $acaoSolicitada->getMetodoSolicitado());
//        $acaoSolicitadaID = $acaoSolicitada->id();//destino
//
//        return DB::table('seg_dependencia as sd')
//            ->join('seg_acao as sa', 'sa.id', '=', 'sd.acao_atual_id')
//            ->where('acao_atual_id', $acaoSolicitadaID)
//            ->where('acao_dependencia_id', $telaID)
//            ->exists();
//    }
}
