<?php

namespace App\Models\Regras;

use App\Models\Entity\Estoque;
use Illuminate\Support\Facades\Auth;

class EstoqueRegras
{
    public static function atualizaEstoquePdvOrigem($p)
    {
        $estoque = Estoque::where('fk_item_cardapio', $p->item_cardapio)->first();

        if(!$estoque) { 
            $estoque = new Estoque(); 
            $estoque->fk_usuario_cad = Auth::user()->id;
            $estoque->created_at = date('Y-m-d H:i:s');
        }else {
            $estoque->fk_usuario_alt = Auth::user()->id;
            $estoque->updated_at = date('Y-m-d H:i:s');
        }

        $estoque->fk_tipo_cardapio = $p->tipo_cardapio;
        $estoque->fk_item_cardapio = $p->item_cardapio;
        $estoque->tipo_movimento = $p->tipoMovimento;

        if($p->tipoMovimento == 'E'){
            $estoque->qtd_atual = ($estoque->qtd_atual + $p->quantidade);

            $estoque->estoque_minimo = $p->estoque_minimo ?? null;
            $estoque->estoque_maximo = $p->estoque_maximo ?? null;
            $estoque->fk_tipo_unidade_medida = $p->tipoUnidadeMedida;
            $estoque->qtd_dose_por_garrafa = $p->qtdDosePorGarrafa;
        }
        else if($p->tipoMovimento == 'S' || $p->tipoMovimento == 'T'){
            $estoque->qtd_atual = ($estoque->qtd_atual - $p->quantidade);                
        }

        $estoque->dt_ultima_atualizacao = date('Y-m-d H:i:s');
        
        $estoque->save();
    }

    public static function atualizaEstoquePdvDestino($p, $id_item_destino)
    {
        $estoque = Estoque::where('fk_tipo_cardapio', $p->pdv_destino)->where('fk_item_cardapio', $id_item_destino)->first();

        if(!$estoque) {
            $estoque = new Estoque();
            $estoque->tipo_movimento = $p->tipoMovimento;
            $estoque->fk_tipo_cardapio = $p->pdv_destino;
            $estoque->fk_item_cardapio = $id_item_destino;
            $estoque->qtd_atual = $p->quantidade;
            $estoque->fk_tipo_unidade_medida = $p->tipoUnidadeMedida;
        }else {
            $estoque->qtd_atual = ($estoque->qtd_atual + $p->quantidade);
        }
        
        $estoque->dt_ultima_atualizacao = date('Y-m-d H:i:s');
        $estoque->fk_usuario_alt = Auth::user()->id;
        $estoque->updated_at = date('Y-m-d H:i:s');
        $estoque->save();
    }

}
