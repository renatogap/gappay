<?php

namespace App\Models\Facade;

use App\Models\Entity\TipoPagamento;

class FormasPagamentoDB extends TipoPagamento
{
    public static function listar()
    {
        return parent::where('id', '!=', 4)
              ->where('id', '!=', 5)
              ->get();
    }
    
}
