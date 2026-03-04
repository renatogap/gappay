<?php

namespace App\Models\Entity;

use GapPay\Seguranca\Models\Entity\AbstractSkeletonModel;

class Produto extends AbstractSkeletonModel
{
    protected $table = "produto";
    protected $guarded = [];

}
