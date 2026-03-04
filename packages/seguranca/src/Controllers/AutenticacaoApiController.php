<?php

namespace GapPay\Seguranca\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use GapPay\Seguranca\Models\Entity\Usuario;
use GapPay\Seguranca\Models\Facade\UsuarioDB;

class AutenticacaoApiController extends Controller
{
    public function login(Request $request)
    {
        $dados = $request->validate([
            'email' => 'required|string',
            'senha' => 'required|string'
        ]);

        if (!$usuario = UsuarioDB::validarAcesso($dados['email'], $dados['senha'])) {
            return response()->json([
                'error' => [
                    'message' => 'Invalid credentials'
                ]
            ], 401);
        }

        return response()->json([
            'token' => $usuario->createToken($usuario->email)->plainTextToken
        ]);
    }
}
