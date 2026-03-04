<?php

namespace GapPay\Seguranca\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use GapPay\Seguranca\Models\Facade\SegAcaoDB;
use GapPay\Seguranca\Models\Regras\AcaoSolicitada;
use GapPay\Seguranca\Models\Regras\Historico;
use Symfony\Component\Routing\Exception\RouteNotFoundException;

class RecursoExiste
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse) $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function handle(Request $request, Closure $next)
    {
        //será usado no próximo middleware que verifica a necessidade de log de acesso a uma ação
        $acaoSolicitada = AcaoSolicitada::getInstance();

        try {
            //Se a rota existir somente no Laravel, será cadastrada automaticamente no banco
            $metodo = preg_replace('/GET,HEAD/i', 'GET', $request->method());
            $acaoSolicitada->configurar(Route::current()->uri(), mb_strtoupper($metodo));
        } catch (RouteNotFoundException $e) {

            return response([
                'message' => 'Ação não cadastrada no banco de dados. Verifique se o método http está correto',
                'method' => $request->method(),
                'uri' => Route::current()->uri()
            ], 404);
        }

        //guarda cache de 2 horas das ações obrigatórias ou até a pessoa fazer logout
//        $acoesObrigatorias = Cache::remember('acoesObrigatorias', 7200, function () {//7200 segundos = 2 horas
//            return SegAcaoDB::acoesObrigatorias();
//        });
        $acoesObrigatorias = SegAcaoDB::acoesObrigatorias();

        //pesquisa a ação nas respostas já em cache (no cache foi guardado uma collection)
        $acaoExiste = $acoesObrigatorias->contains('nome', $acaoSolicitada->getNome());

        if ($acaoExiste) {
            return $next($request);
        }

        //Registrando log de acesso se for necessário
        if ($acaoSolicitada->getAcao()?->log_acesso) {
            $h = Historico::getInstance();
            $h->criarLogVisualizacao($acaoSolicitada->getAcao()->id, $request->getRequestUri());
        }

        return $next($request);
    }
}
