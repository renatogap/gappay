<?php

namespace App\Http\Controllers;

use App\Http\Requests\InformacaoUsuarioRequest;
use App\Http\Requests\UsuarioLocalRequest;
use App\Models\Entity\CardapioTipo;
use App\Models\Entity\UsuarioTipoCardapio;
use App\Models\Facade\SegPerfilDB;
use App\Models\Facade\UsuarioLocalDB;
use App\Models\Regras\UsuarioLocalRegras;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use GapPay\Seguranca\Models\Entity\Usuario;
use GapPay\Seguranca\Models\Facade\UsuarioDB;
use GapPay\Seguranca\Models\Regras\AutenticacaoRegras;
use GapPay\Seguranca\Models\Regras\DB;

class UsuarioLocalController extends Controller
{
    public function index()
    {
        $p = (object) request()->all();
        $nome = request('nome') ?? null;
        $email = request('email') ?? null;

        $usuarios = UsuarioLocalDB::grid($p);
        return view('usuario.index', compact('usuarios', 'nome', 'email'));
    }

    public function criar()
    {
        $aPerfisCadastrados = UsuarioLocalDB::perfis();
        $aCardapio = CardapioTipo::orderBy('nome')->get();

        return view('usuario.create', compact('aCardapio', 'aPerfisCadastrados'));
    }

    public function store(UsuarioLocalRequest $request)
    {
        DB::beginTransaction();
        try {
            $usuario = UsuarioLocalRegras::criar($request->validatedForm());

            Db::commit();

            return response()->json(array(
                'message' => 'Usuário cadastrado com sucesso.',
                'url' => url('admin/usuario/editar/' . $usuario->id)
            ));
        } catch (Exception $e) {
            DB::rollback();
            return response()->json(array('message' => $e->getMessage()), 422);
        }
    }

    public function editar(Usuario $usuario)
    {
        $aCardapio = CardapioTipo::orderBy('nome')->get();
        $aCardapioCadastrados = UsuarioTipoCardapio::where('fk_usuario', $usuario->id)->get();
        $aPerfisCadastrados = UsuarioLocalDB::perfis();
        $aPerfil = UsuarioLocalDB::perfilUsuario($usuario->id);

        return view('usuario.editar', compact('usuario', 'aPerfil', 'aPerfisCadastrados', 'aCardapioCadastrados', 'aCardapio'));
    }

    public function update(UsuarioLocalRequest $request, $usuario)
    {
        try {
            UsuarioLocalRegras::atualizar($request->validatedForm($usuario));

            DB::commit();
            return response([
                'message' => 'Usuário atualizado com sucesso'
            ]);
        } catch (Exception $e) {
            DB::rollback();
            return response(exibirErro($e, 'Falha ao atualizar Usuário'), 500);
        }
    }

    public function destroy(Usuario $usuario)
    {
        DB::beginTransaction();

        try {
            UsuarioLocalRegras::excluir($usuario);

            DB::commit();
            return response()->json(['message' => 'Usuário excluído com sucesso'], 200);
        } catch (Exception $e) {
            DB::rollback();
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function grid(Request $request)
    {
        $p = (object)$request->all();
        $p->usuario = Auth::user();
        return response(UsuarioLocalDB::grid($p));
    }

    public function reativar($usuario)
    {
        try {
            UsuarioLocalRegras::reativarUsuario($usuario);
            return response()->json(['message' => 'O Status do Usuário foi atualizado com sucesso'], 200);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function info(InformacaoUsuarioRequest $request)
    {
        if ($usuarioCadastrado = UsuarioLocalDB::info($request->cpf)) {
            return response()->json($usuarioCadastrado, Response::HTTP_ALREADY_REPORTED);
        } else {
            return response(null, Response::HTTP_NO_CONTENT);
        }
    }

    /**
     * @throws Exception
     */
    public function login()
    {
        //verificar se o usuário já está autenticado, caso esteja redirecionar para home
        if (Auth::check()) {
            return redirect()->route('home');
        }

        return view('login');
    }

    public function logout()
    {
        AutenticacaoRegras::logout();
        return redirect()->route('login');
    }

    public function home()
    {
        return view('home');
    }

    public function telaAtualizacaoSenhaViaHash(Request $request)
    {
        if (!$request->query('hash')) {
            return redirect()->route('login');
        }
        return view('layouts.vue');
    }
}
