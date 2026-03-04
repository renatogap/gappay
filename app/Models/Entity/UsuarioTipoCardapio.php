<?php

namespace App\Models\Entity;

use GapPay\Seguranca\Models\Entity\AbstractSkeletonModel;

class UsuarioTipoCardapio extends AbstractSkeletonModel
{
    protected $table = "usuario_tipo_cardapio";
    public $timestamps = false;
    protected $guarded = [];

}
