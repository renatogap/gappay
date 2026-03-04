<?php

namespace GapPay\Seguranca\Models\Facade;

use GapPay\Seguranca\Models\Entity\Usuario;
use GapPay\Seguranca\Models\Entity\UsuarioSistema;

class AutorizacaoDB
{
    public static function possuiPermissaoSistema(Usuario $usuario, int $sistema_id): UsuarioSistema|null
    {
        return UsuarioSistema::where('sistema_id', $sistema_id)
            ->where('usuario_id', $usuario->id)
            ->where('status', 1)
            ->first();
    }
}
