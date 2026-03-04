<?php

namespace App\Models\Facade;

use App\Models\Entity\EntradaCredito;
use Illuminate\Support\Facades\DB;

class FaturamentoDB
{
    public static function totalDeRecargasAgrupadasPorHora($data)
    {
        return EntradaCredito::select(
                    DB::raw('HOUR(data) as hora'),
                    DB::raw('COUNT(*) as total_recargas')
                )
                ->whereBetween('data', ["$data 00:00", "$data 23:59"])
                ->groupBy(DB::raw('HOUR(data)'))
                ->orderBy(DB::raw('HOUR(data)'))
                ->get();
    }

    public static function totalDeRecargasPorMes($ano)
    {
        return EntradaCredito::select(
                    DB::raw('MONTH(data) as mes'),
                    DB::raw("SUM(valor) as total_recargas")
                    //DB::raw("FORMAT(SUM(valor), 2, 'de_DE') as total_recargas")
                )
                ->whereBetween('data', ["{$ano}-01-01 00:00", "{$ano}-12-31 23:59"])
                ->groupBy(DB::raw('MONTH(data)'))
                ->orderBy(DB::raw('MONTH(data)'))
                ->get();
    }

    public static function valorDoMaiorMesFaturado($ano)
    {
        return EntradaCredito::select(
                    DB::raw('MONTH(data) as mes'),
                    DB::raw("(SUM(valor)) as total")
                )
                ->whereBetween('data', ["{$ano}-01-01 00:00", "{$ano}-12-31 23:59"])
                ->groupBy(DB::raw('MONTH(data)'))
                ->orderBy(DB::raw('SUM(valor)'), 'DESC')
                ->limit(1)
                ->first();
    }

    public static function valorTotalDoFaturamentoAnual($ano)
    {
        return EntradaCredito::select(DB::raw('SUM(valor) as total'))
                                ->whereBetween('data', ["{$ano}-01-01 00:00", "{$ano}-12-31 23:59"])
                                ->first();
    }
}
