<?php

namespace GapPay\Seguranca\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use GapPay\Seguranca\Models\Regras\AutenticacaoRegras;
use GapPay\Seguranca\Models\Regras\RedefinicaoSenhaRegras;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AutenticacaoController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required',
            'senha' => 'required|string'
        ]);

        try {
            AutenticacaoRegras::autenticacao(
                $request->email, 
                $request->senha, 
                implode(',', $request->ips()),
                $request->userAgent()
            );

            //return response(UsuarioRegras::info(Auth::user()));
            return redirect()->route('home');

        } catch (\Exception $e) {
            return redirect()->route('tela.login')->with('error', $e->getMessage())->withInput();
        } 
    }

    public function logout(Request $request)
    {
        try {
            AutenticacaoRegras::logout();
            if (!$request->ajax()) {
                return redirect()->route('login');
            }
            return response(null, Response::HTTP_NO_CONTENT);
        } catch (\Exception $e) {
            return response($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }

    }

    public function enviarEmail()
    {
        try {
            $usuario = AutenticacaoRegras::enviarEmail(request('email'));
            //show part of email on $usuario->email

            return response([
                'message' => 'E-mail enviado com sucesso para ' . AutenticacaoRegras::ocultarEmail($usuario->email)
            ]);
        } catch (\Exception $e) {
            return response(exibirErro($e, 'Link inválido'), 422);
        }
    }

    public function validarHash($hash)
    {
        DB::beginTransaction();
        try {
            RedefinicaoSenhaRegras::validarHash($hash);
            DB::commit();

            return response('', 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response(exibirErro($e, 'Link inválido'), 422);
        }
    }

    public function atualizarSenha(Request $request, $hash)
    {
        $request->validate([
            'senha' => 'required|confirmed|min:8',
        ],
            [
                'senha.confirmed' => 'As senhas não estão iguais'
            ]);

        DB::beginTransaction();
        try {
            RedefinicaoSenhaRegras::atualizarSenha($hash, request('senha'));
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
