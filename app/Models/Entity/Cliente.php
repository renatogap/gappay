<?php

namespace App\Models\Entity;

use GapPay\Seguranca\Models\Entity\AbstractSkeletonModel;

class Cliente extends AbstractSkeletonModel
{
    protected $table = "cliente";
    protected $guarded = [];
}
