<?php

namespace GapPay\Seguranca\Models\Regras;

use GapPay\Seguranca\Models\Entity\LinkTemporario;
use GapPay\Seguranca\Models\Entity\Usuario;

class RedefinicaoSenhaRegras
{
    public static function atualizarSenha(string $hash, string $senha): void
    {
        $linkTemporario = self::validarHash($hash);
        $usuario = Usuario::findOrFail($linkTemporario->fk_usuario);

        $usuario->senha = $senha;
        $usuario->save();

        $linkTemporario->usado = true;
        $linkTemporario->save();
    }

    /**
     * @param string $hash
     * @return LinkTemporario
     * @throws \Exception
     */
    public static function validarHash(string $hash): LinkTemporario
    {
        return LinkTemporario::where('hash', $hash)
            ->where('data_expiracao', '>=', date('Y-m-d H:i:s'))
            ->where('usado', false)
            ->orderBy('id', 'desc')
            ->firstOrFail();
    }
}
