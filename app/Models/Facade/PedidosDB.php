<?php

namespace App\Models\Facade;

use App\Models\Entity\Pedido;
use Illuminate\Support\Facades\DB;

class PedidosDB extends Pedido
{
    public static function totalDePedidosAgrupadoPorHora($data)
    {
        return Pedido::select(
                    DB::raw('HOUR(dt_pedido) as hora'),
                    DB::raw('COUNT(*) as total_pedidos')
                )
                ->whereBetween('dt_pedido', ["$data 00:00", "$data 23:59"])
                ->groupBy(DB::raw('HOUR(dt_pedido)'))
                ->orderBy(DB::raw('HOUR(dt_pedido)'))
                ->get();
    }
}
