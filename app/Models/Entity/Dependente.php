<?php

namespace App\Models\Entity;

use GapPay\Seguranca\Models\Entity\AbstractSkeletonModel;

class Dependente extends AbstractSkeletonModel
{
    protected $table = "dependente";
    protected $guarded = [];
}
