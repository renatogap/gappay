<?php

namespace App\Models\Facade;

use App\Models\Entity\Cardapio;
use App\Models\Entity\CardapioCategoria;
use App\Services\DeepLService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class CardapioDB extends Model
{
    public static  function pesquisar($id_tipo_cardapio = null)
    {
        $db = DB::table('cardapio as c')
            ->join('cardapio_tipo as ct', 'ct.id', '=', 'c.fk_tipo_cardapio')
            ->join('cardapio_categoria as cc', 'cc.id', '=', 'c.fk_categoria')
            ->select([
                'ct.nome as tipo_cardapio',
                'cc.nome as categoria',
                'cc.nome_en as categoria_en',
                'c.*'
            ])
            ->where('c.status', 1)
            ->orderBy('cc.nome')
            ->orderBy('c.nome_item');

        if(!is_array($id_tipo_cardapio)){
            $db->where('c.fk_tipo_cardapio', $id_tipo_cardapio);
        }else {
            $db->whereIn('c.fk_tipo_cardapio', $id_tipo_cardapio);
        }
        
        $cardapio = $db->get();

        $myItems = [];
        $myCardapio = [];
        $locale = session('locale');

        foreach($cardapio as $i => $c) {
            $myItems[$c->tipo_cardapio][] = $c;
        }

        foreach($myItems as $tipo => $itens) {
            foreach($itens as $i => $item){

                if(!$item->categoria_en) {
                    $deepl = new DeepLService();

                    $categoria = CardapioCategoria::find($item->fk_categoria);
                    $categoria->nome_en = $deepl->translate($item->categoria, 'EN');
                    $categoria->desabilitarLog();
                    $categoria->save();

                    $item->categoria_en = $categoria->nome_en;
                }

                if(!$item->nome_item_en) {
                    $deepl = new DeepLService();

                    $cardapio = Cardapio::find($item->id);
                    $cardapio->nome_item_en = $deepl->translate($item->nome_item, 'EN');
                    $cardapio->desabilitarLog();
                    $cardapio->save();

                    $item->nome_item_en = $cardapio->nome_item_en;
                }

                if(!$item->detalhe_item_en) {
                    $deepl = new DeepLService();

                    $cardapio = Cardapio::find($item->id);
                    $cardapio->detalhe_item_en = $item->detalhe_item ? $deepl->translate($item->detalhe_item, 'EN') : '';
                    $cardapio->desabilitarLog();
                    $cardapio->save();

                    $item->detalhe_item_en = $cardapio->detalhe_item_en;
                }

                $categoria = (!isset($locale) ? $item->categoria : ($locale=='PT' ? $item->categoria : $item->categoria_en));

                $myCardapio[$tipo][$categoria][] = $item;
            }
        }

        return $myCardapio;
    }

    public static  function pesquisarAdmin($id_tipo_cardapio = null)
    {
        $db = DB::table('cardapio as c')
            ->join('cardapio_tipo as ct', 'ct.id', '=', 'c.fk_tipo_cardapio')
            ->join('cardapio_categoria as cc', 'cc.id', '=', 'c.fk_categoria')
            ->select([
                'ct.nome as tipo_cardapio',
                'cc.nome as categoria',
                'c.*'
            ])
            ->where('c.status', '!=', 2)
            ->orderBy('ct.nome')
            ->orderBy('cc.nome')
            ->orderBy('c.nome_item');

        if(!is_array($id_tipo_cardapio)){
            $db->where('c.fk_tipo_cardapio', $id_tipo_cardapio);
        }else {
            $db->whereIn('c.fk_tipo_cardapio', $id_tipo_cardapio);
        }
        
        $cardapio = $db->get();

        $myItems = [];
        $myCardapio = [];

        foreach($cardapio as $i => $c) {
            $myItems[$c->tipo_cardapio][] = $c;
        }

        foreach($myItems as $tipo => $itens) {
            foreach($itens as $i => $item){
                $myCardapio[$tipo][$item->categoria][] = $item;
            }
        }

        return $myCardapio;
    }
    
}
