<?php

namespace GapPay\Seguranca\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use GapPay\Seguranca\Models\Facade\UsuarioDB;
use GapPay\Seguranca\Models\Regras\UsuarioRegras;
use GapPay\Seguranca\Request\UsuarioRequest;

class UsuarioController extends Controller
{
    public function grid(Request $request)
    {
        $p = (object)$request->all();
        return response(UsuarioDB::grid($p, Auth::user()));
    }

    public function store(UsuarioRequest $request)
    {
        $p = (object)$request->all();

        try {

            $usuario = UsuarioRegras::cadastrar($p);

            return response([
                'msg' => 'Usuário criado com sucesso',
                'usuario' => $usuario->id
            ]);
        } catch (\Exception $e) {
            return response(exibirErro($e, 'Falha ao cadastrar usuário.'));
        }
    }

    public function configuracoes()
    {
        $usuario = Auth::user();

        return response()->json([
            'nome' => $usuario->nome,
            'email' => $usuario->email,
            'cpf' => $usuario->cpf,
            'nascimento' => $usuario->nascimento,
        ]);
    }

    /**
     * Usado quando um usuário atualiza a tela com F5 ou qualquer tecla, ou função equivalente
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function info()
    {
        if (Auth::hasUser()) {
            $usuario = Auth::user();
            return response(UsuarioRegras::info($usuario));
        } else {
            return null;
        }
    }

    public function dadosSRH($cpf)
    {
        return UsuarioDB::dadosSRH($cpf);
    }

    public function salvarConfiguracoes(Request $request): \Illuminate\Http\JsonResponse
    {
        $request->validate([
            'nome' => 'required',
            'email' => 'required|email',
            'nascimento' => 'required',
            'cpf' => 'required'
        ]);

        return response()->json(
            'bacana'
        );
    }

    public function edit($id)
    {
        return UsuarioRegras::editar($id);
    }
}
