<?php

namespace App\Models\Entity;

use GapPay\Seguranca\Models\Entity\AbstractSkeletonModel;

class EstoqueEntrada extends AbstractSkeletonModel
{
    protected $table = "estoque_entrada";
    protected $guarded = [];

}
