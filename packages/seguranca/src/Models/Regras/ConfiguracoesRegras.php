<?php

namespace GapPay\Seguranca\Models\Regras;

use GapPay\Seguranca\Models\Entity\Usuario;

class ConfiguracoesRegras
{
    public static function atualizar(Usuario $usuario, \stdClass $p)
    {
        $usuario->nome = $p->nome;
        $usuario->email = $p->email;
        $usuario->nascimento = $p->nascimento;
        $usuario->cpf = preg_replace('/\D/', '', $p->cpf);
        $usuario->save();
    }
}
