<?php

namespace App\Models\Entity;

use GapPay\Seguranca\Models\Entity\AbstractSkeletonModel;

class EstoqueSaida extends AbstractSkeletonModel
{
    protected $table = "estoque_saida";
    protected $guarded = [];

}
