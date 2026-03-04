<?php

namespace App\Models\Entity;

use GapPay\Seguranca\Models\Entity\AbstractSkeletonModel;

class Estoque extends AbstractSkeletonModel
{
    protected $table = "estoque";
    protected $guarded = [];

}
