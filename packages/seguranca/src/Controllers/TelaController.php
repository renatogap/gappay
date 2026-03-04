<?php

namespace GapPay\Seguranca\Controllers;;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use GapPay\Seguranca\Models\Regras\SegDependenciaRegras;
use GapPay\Seguranca\Models\Regras\TelaRegras;

class TelaController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'url' => 'required',
            'nome_amigavel' => 'required',
            'grupo' => 'required'
        ]);

        try {
            TelaRegras::cadastrar($request->url, $request->nome_amigavel, $request->grupo);
            return response([
                'message' => 'Tela cadastrada com sucesso'
            ]);
        } catch (\Exception $e) {
            return response(exibirErro($e, 'Não foi possível cadastrar Tela.'), 500);
        }
    }

    public function analisar()
    {
        return response(TelaRegras::analisar(request('rotas')));
    }
}
