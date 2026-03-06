<?php

namespace App\Models\Facade;

use App\Models\Entity\Dependente;
use Illuminate\Support\Facades\DB;

class DependenteDB extends Dependente
{
    public static function find($id)
    {
        return DB::table('dependente as d')
                    ->join('cartao_cliente as cc', 'cc.id', 'd.fk_cartao_cliente')
                    ->join('cartao as c', 'c.id', 'cc.fk_cartao')
                    ->select(['d.*', 'cc.fk_cartao', 'c.codigo'])
                    ->where('d.id', $id)
                    ->first();
    }

    public static function todos($id_cliente)
    {
        $dependentes = DB::table('dependente as d')
            ->join('grau_parentesco as g', 'g.id', '=', 'd.fk_grau_parentesco')
            ->where('d.fk_cliente', $id_cliente)
            ->get(['d.*', 'g.nome as grau_parentesco']);

        return $dependentes;
    }

    public static function ativos($id_cliente)
    {
        $dependentes = DB::table('dependente as d')
            ->join('grau_parentesco as g', 'g.id', '=', 'd.fk_grau_parentesco')
            ->join('cartao_cliente as cc', 'cc.id', 'd.fk_cartao_cliente')
            ->join('cartao as c', 'c.id', 'cc.fk_cartao')
            ->where('d.fk_cliente', $id_cliente)
            ->where('d.status', 1)
            ->get(['d.*', 'g.nome as grau_parentesco', 'cc.fk_cartao', 'c.codigo']);

        return $dependentes;
    }
    
}
