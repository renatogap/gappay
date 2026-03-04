<?php

namespace App\Http\Controllers;

use App\Http\Resources\PerfilResource;
use App\Models\Facade\SegPerfilDB;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use GapPay\Seguranca\Models\Entity\SegPerfil;
use GapPay\Seguranca\Models\Facade\SegAcaoDB;
use GapPay\Seguranca\Models\Form\PerfilForm;
use GapPay\Seguranca\Models\Regras\DB;
use GapPay\Seguranca\Models\Regras\PerfilRegras;

class PerfilController extends Controller
{
    public function index()
    {
        if (!request()->ajax()) {
            return view('layouts.vue');
        }

        $perfis = SegPerfilDB::comboPerfil(Auth::user());
        $acoesDestaque = SegAcaoDB::listaAcoesDestaque();
        $grupos = $acoesDestaque->groupBy('grupo');

        return response([
            'perfis' => $perfis,
            'acoes' => $acoesDestaque,
            'grupos' => $grupos
        ]);
    }

    public static function create()
    {
        if (!request()->ajax()) {
            return view('layouts.vue');
        }

        $usuario = Auth::user();
        return new PerfilResource([
            'usuario' => $usuario
        ]);
    }

    public static function grid(Request $request)
    {
        $p = (object)$request->all();
        return response(SegPerfilDB::grid($p));
    }

    public function store(Request $request)
    {
        $dados = $request->validate([
            'nome' => 'required',
            'permissoes' => 'array'
        ]);

        DB::beginTransaction();
        try {

            $perfil = PerfilRegras::cadastrar(PerfilForm::create($dados));
            DB::commit();

            return response([
                'message' => 'Perfil criado com sucesso',
                'perfil' => $perfil->id
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            return response(exibirErro($e, 'Falha ao criar perfil.'));
        }
    }

    public static function edit($id)
    {
        if (!request()->ajax()) {
            return view('layouts.vue');
        }

        $p = new \stdClass();
        $p->id = $id;
        $p->usuario = Auth::user();

        return response(PerfilRegras::telaEdicao($p));
    }

    public function update(Request $request, $perfil)
    {
        $dados = $request->validate([
            'nome' => 'required',
            'permissoes' => 'array'
        ], [
            'id.required' => 'Não foi possível identificar o ID do perfil para edição'
        ]);

        $dados['id'] = $perfil;

        DB::beginTransaction();
        try {
            PerfilRegras::atualizar(PerfilForm::create($dados));

            DB::commit();
            return response([
                'message' => 'Perfil atualizado com sucesso'
            ]);
        } catch (ModelNotFoundException $e) {
            DB::rollback();
            return response(exibirErro($e, 'Perfil não encontrado'), 404);
        }
        catch (\Exception $e) {
            DB::rollback();
            return response(exibirErro($e, 'Falha ao atualizar'), 500);
        }
    }

    public function delete(SegPerfil $perfil)
    {
        DB::beginTransaction();
        try {

            PerfilRegras::excluir($perfil);

            DB::commit();
            return response([
                'message' => 'Perfil excluído com sucesso'
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            return response(exibirErro($e, 'Falha ao excluir perfil'), 500);
        }
    }
}
