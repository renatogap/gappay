<?php

namespace App\Models\Entity;

use GapPay\Seguranca\Models\Entity\AbstractSkeletonModel;

class SituacaoCartao extends AbstractSkeletonModel
{
    protected $table = "situacao_cartao";
    protected $guarded = [];

}
