<?php

namespace GapPay\Seguranca\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use GapPay\Seguranca\Models\Entity\Usuario;
use GapPay\Seguranca\Models\Regras\AcaoSolicitada;
use GapPay\Seguranca\Models\Regras\UsuarioLogado;

class Autorizacao
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse) $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        \Log::info('Autorização middleware: checking auth', [
            'auth_check' => Auth::check(),
            'user_id' => Auth::id(),
            'session_id' => session()->getId(),
            'url' => $request->url(),
            'method' => $request->method()
        ]);

        if (!Auth::check()) { // Usuário não autenticado
            \Log::warning('Autorização middleware: usuário não autenticado, redirecionando para login');
            if (!$request->ajax()) { // Se não for uma requisição AJAX, redireciona para a tela de login
                return redirect()->route('tela.login');
            }
            abort(401, 'Usuário não autenticado');
        }

        /**
         * @var Usuario $usuario
         */
        $usuario = Auth::user();
        if ($usuario->isRoot()) {//root passa direto para próxima camada
            \Log::info('Autorização middleware: usuário é root, permitindo acesso sem verificação de permissão');
            return $next($request);
        }

        $acaoSolicitada = AcaoSolicitada::getInstance();

        if (!UsuarioLogado::permissaoUrl($acaoSolicitada)) {
            \Log::warning('Seu usuário não possui permissão para acessar o recurso solicitado', [
                'user_id' => $usuario->id,
                'acao' => $acaoSolicitada
            ]);

            die('Seu usuário não possui permissão para acessar o recurso solicitado');
        }

        \Log::info('Autorizacao middleware: user authorized, proceeding');
        return $next($request);
    }
}
