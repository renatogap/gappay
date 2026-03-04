<?php

namespace GapPay\Seguranca\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use GapPay\Seguranca\Models\Entity\Usuario;
use GapPay\Sso\CookieSSO;
use Symfony\Component\HttpFoundation\Response;

class AutenticacaoUnica
{
    /**
     * Handle an incoming request.
     *
     * @param \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response) $next
     * @throws \Exception
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!config('policia.chave_sso')) {
            throw new \Exception('Chave SSO não configurada');
        }

        $cookie = CookieSSO::read(config('policia.chave_sso'));

        if ($cookie && !Auth::check()) {//se houver cookie e usuário sem sessão, então autentica
            $usuario = Usuario::find($cookie);
            if ($usuario) {
                Auth::login($usuario);
                CookieSSO::make($usuario->id, config('policia.chave_sso'));//renova tempo de vida do cookie
            }
            return $next($request);
        }

        if ($cookie && Auth::check()) {//se houver cookie e usuário com sessão, então renova tempo de vida do cookie
            CookieSSO::make(Auth::id(), config('policia.chave_sso'));//renova tempo de vida do cookie
            return $next($request);
        }

        //se não houver cookie sso, então continua para próxima camada
        return $next($request);
    }
}
