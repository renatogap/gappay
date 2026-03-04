<?php

namespace GapPay\Seguranca\Controllers;

use App\Http\Controllers\Controller;
use App\Rules\CPFValidacao;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use GapPay\Seguranca\Models\Regras\ConfiguracoesRegras;
use GapPay\Seguranca\Models\Regras\DB;

class ConfiguracoesController extends Controller
{
    public function edit()
    {
        $usuario = Auth::user();

        return [
            'id' => $usuario->id,
            'nome' => $usuario->nome,
            'email' => $usuario->email,
            'nascimento' => $usuario->nascimento,
            'cpf' => $usuario->cpf
        ];
    }

    public function update(Request $request)
    {
        $request->validate([
            'nome' => 'required',
            'email' => 'required|email',
            'nascimento' => 'required|before:tomorrow',
            'cpf' => ['required', new CPFValidacao()]
        ]);

        DB::beginTransaction();
        try {
            $usuario = Auth::user();
            $p = (object)$request->all();

            ConfiguracoesRegras::atualizar($usuario, $p);

            DB::commit();
            return response([
                'message' => 'Registro efetuado com sucesso'
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            return response(exibirErro($e, 'Falha ao atualizar'), 500);
        }
    }

    public function senha(Request $request)
    {
        $p = $request->validate([
            'senha_atual' => 'required|min:8',
            'senha' => 'required|min:8|confirmed',
        ]);

        $usuario = Auth::user();

        DB::beginTransaction();
        try {
            if (!$usuario || !Hash::check($p['senha_atual'], $usuario->senha2)) {
                throw new \Exception('Senha atual inválida');
            }

            $usuario->primeiro_acesso = false;
            $usuario->senha = $p['senha'];
            $usuario->save();

            DB::commit();
            return response([
                'message' => 'Senha atualizada com sucesso'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response(exibirErro($e, 'Falha ao atualizar senha'), 422);
        }

    }
}
