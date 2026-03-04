<?php

namespace GapPay\Seguranca\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use GapPay\Seguranca\Models\Facade\SegAcaoDB;
use GapPay\Seguranca\Models\Regras\AcaoRegras;
use GapPay\Seguranca\Request\AcaoRequest;

class AcaoController extends Controller
{
    public function grid(Request $request): Response
    {
        $p = (object)$request->all();
        return response(SegAcaoDB::grid($p));
    }

    public function create(): Response
    {
        return response(AcaoRegras::telaCadastro());
    }

    /**
     * @throws \Throwable
     */
    public function store(AcaoRequest $request): Response
    {
        DB::beginTransaction();
        try {
            $acao = AcaoRegras::criar($request->validatedForm());

            DB::commit();
            return response(['message' => 'Ação cadastrada com sucesso.', 'acao' => $acao->id], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response(exibirErro($e, 'Não foi possível criar ação. Tente novamente.'), 422);
        }
    }

    public function edit($acao): Response
    {
        $resposta = AcaoRegras::telaEdicao($acao);
        return response($resposta);
    }

    /**
     * @param AcaoRequest $request
     * @return Response
     * @throws \Throwable
     */
    public function update(AcaoRequest $request): Response
    {

        try {
             DB::beginTransaction();
            AcaoRegras::atualizar($request->validatedForm());

            DB::commit();
            return response([
                'message' => 'Ação atualizada com sucesso'
            ]);
        } catch (ModelNotFoundException $e) {
            DB::rollback();
            return response(exibirErro($e, 'Ação não encontrada'), 404);
        } catch (\Exception $e) {
            DB::rollback();
            return response(exibirErro($e, 'Falha ao atualizar ação'), 500);
        }
    }

    /**
     * @throws \Throwable
     */
    public function destroy($acao): Response
    {
        DB::beginTransaction();
        try {
            AcaoRegras::excluir($acao);
            DB::commit();
            return response([
                'message' => 'Ação excluída com sucesso'
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            return response(exibirErro($e, 'Falha ao excluir ação'), 500);
        }
    }
}
