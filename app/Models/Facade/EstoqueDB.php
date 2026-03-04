<?php

namespace App\Models\Facade;

use App\Models\Entity\Estoque;
use Illuminate\Support\Facades\DB;

class EstoqueDB extends Estoque
{
    public static function saldoEstoqueProdutoCardapio($item_cardapio_id)
    {
        $quantidade = DB::table('estoque as e')
                        ->select([
                            DB::raw("(select 
                            (
                                select COALESCE(sum(ee.quantidade), 0) as quantidade
                                from estoque_entrada ee 
                                where ee.fk_item_cardapio = $item_cardapio_id
                            ) 
                            -
                            (
                                select COALESCE(sum(es.quantidade), 0) as quantidade
                                from estoque_saida es 
                                where es.fk_item_cardapio = $item_cardapio_id
                            ))
                            as qtd_atual")
                        ])
                        ->first();

        return $quantidade->qtd_atual;
    }


    public static function produtosEmEstoqueDoPDV($tipo_cardapio_id)
    {
        return DB::table('estoque as e')
                ->join('cardapio as c', 'c.id', '=', 'e.fk_item_cardapio')
                ->select([
                    'c.id',
                    'c.nome_item',
                    'c.fk_produto'
                ])
                ->where('e.fk_tipo_cardapio', $tipo_cardapio_id)
                ->orderBy('c.nome_item')
                ->get();
    }
    
}
