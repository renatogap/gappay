<?php

namespace GapPay\Seguranca\Models\Regras;

use GapPay\Seguranca\Models\Entity\SegGrupo;
use GapPay\Seguranca\Models\Entity\Usuario;

class EdicaoUsuario
{
    public static function atualizar(\stdClass $p): void
    {
        $usuario = Usuario::find($p->id);
        $usuario->nome = $p->nome;
        $usuario->cpf = $p->cpf;
        $usuario->email = $p->email;

        if ($p->senha) {
            $usuario->senha = $p->senha;
        }
        $usuario->save();

        //alterando perfil do usuário
        //tirando do usuário todos os perfis que não foram solicitados
       $aPerfis = SegGrupo::where('usuario_id', $usuario->id)->get();
    }
}
