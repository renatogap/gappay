<?php

namespace App\Models\Regras;

use Illuminate\Support\Facades\Auth;
use GapPay\Seguranca\Models\Entity\Usuario;
use GapPay\Seguranca\Models\Entity\UsuarioSistema;
use GapPay\Seguranca\Models\Regras\UsuarioForm;
use GapPay\Seguranca\Models\Regras\UsuarioRegras;

class UsuarioLocalRegras extends UsuarioRegras
{
    public static function atualizar(UsuarioForm $p): Usuario
    {
        //edita um usuário seguindo as regras padrão do segurança (não remova as linhas abaixo)
        return parent::editar($p);
    }

    public static function criar(UsuarioForm $p): Usuario
    {
        //escreva aqui regras específicas para este sistema (se houver)

        //cria um usuário seguindo as regras padrão do segurança (não remova as linhas abaixo)
        return parent::cadastrar($p);
    }

    public static function excluir(Usuario $usuario): void
    {
        //escreva aqui regras específicas para este sistema (se houver)

        //não remova a linha abaixo
        parent::excluir($usuario);//rotinas padrão para exclusão de usuário
    }

    public static function reativarUsuario(int $usuario): void
    {
    
        $usuarioSistema = UsuarioSistema::where('usuario_id', $usuario)
            ->where('sistema_id', config('policia.codigo'))
            ->firstOrFail();


        //Se for para ativar, remove a situação de expirado também
        //atualizando a data do último acesso para agora
        if (!$usuarioSistema->ativo) {
            $usuarioSistema->ultimo_acesso = date('Y-m-d');
        }

        $usuarioEdicao = Auth::user()->id;

        $usuarioSistema->fk_usuario_edicao = $usuarioEdicao;
        $usuarioSistema->status = !$usuarioSistema->ativo;
        $usuarioSistema->ativo = !$usuarioSistema->ativo;
        $usuarioSistema->updated_at = date('Y-m-d H:i:s');
        $usuarioSistema->save();
    }
}
