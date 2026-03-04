<?php

namespace App\Models\Entity;

use GapPay\Seguranca\Models\Entity\AbstractSkeletonModel;

class SaidaCredito extends AbstractSkeletonModel
{
    protected $table = "saida_credito";
    public $timestamps = false;
    protected $guarded = [];
}
