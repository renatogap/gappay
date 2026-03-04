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
        if (!Auth::check()) { // Usuário não autenticado
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
            return $next($request);
        }

        $acaoSolicitada = AcaoSolicitada::getInstance();

        //usuário deve trocar senha e não está na tela de troca de senha
//        if ($usuario->deveTrocarSenha()
//            && $acaoSolicitada->getNome() !== 'configuracoes/senha' //url do front
//            && $acaoSolicitada->getNome() !== 'api/configuracoes/senha'//url do back
//            && $acaoSolicitada->getNome() !== 'api/usuario/configuracoes'//url do back que popula a tela de configurações
//            && $acaoSolicitada->getNome() !== 'api/usuario/info'//url do back que verifica se usuário está logado
//        ) {
//            return response(['url' => '/configuracoes/senha'], 307);//307 - Temporary Redirect
//        }

        //usuário deve atualizar seu cadastro e não está na tela de atualizar cadastro
        // if ($usuario->cadastroIncompleto() && $acaoSolicitada->getNome() !== 'api/usuario/configuracoes') {
        //     return response(['url' => '/configuracoes'], 307);
        // }

        if (!UsuarioLogado::permissaoUrl($acaoSolicitada)) {
            return abort(403, 'Seu usuário não possui permissão para acessar o recurso solicitado');
        }

        return $next($request);
    }
}
