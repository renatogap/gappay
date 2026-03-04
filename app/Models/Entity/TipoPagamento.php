<?php

namespace App\Models\Entity;

use GapPay\Seguranca\Models\Entity\AbstractSkeletonModel;

class TipoPagamento extends AbstractSkeletonModel
{
    protected $table = "tipo_pagamento";
    protected $guarded = [];

}
