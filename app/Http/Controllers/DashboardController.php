<?php

namespace App\Http\Controllers;

use App\Models\Facade\FaturamentoDB;
use App\Models\Facade\PedidosDB;

class DashboardController extends Controller
{
    public function index()
    {
        return view('dashboard.index');
    }

    public function pedidosPorHora($data)
    {
        $pedidos = PedidosDB::totalDePedidosAgrupadoPorHora($data);
        $maior = $pedidos->max('total_pedidos');
        return response()->json(['pedidos' => $pedidos, 'maior' => $maior]);
    }

    public function recargasPorHora($data)
    {
        $recargas = FaturamentoDB::totalDeRecargasAgrupadasPorHora($data);
        $maior = $recargas->max('total_recargas');
        return response()->json(['recargas' => $recargas, 'maior' => $maior]);
    }

    public function recargasPorMes($ano)
    {
        $recargas = FaturamentoDB::totalDeRecargasPorMes($ano);

        $faturamentoTotalAnual = (FaturamentoDB::valorTotalDoFaturamentoAnual($ano))->total;

        $maior = (FaturamentoDB::valorDoMaiorMesFaturado($ano))->total;

        return response()->json(['recargas' => $recargas, 'maior' => $maior, 'total' => $faturamentoTotalAnual]);
    }
}
