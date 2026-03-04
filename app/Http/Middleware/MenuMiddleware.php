<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use GapPay\Seguranca\Models\Regras\UsuarioLogado;

class MenuMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if ($request->ajax()) {
            return $next($request);
        }

        $aMenu = UsuarioLogado::menus();

        View::share('menu', $aMenu); //variável menu fica disponível para qualquer view
        return $next($request);
    }
}
