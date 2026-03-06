<?php

namespace App\Models\Entity;

use GapPay\Seguranca\Models\Entity\AbstractSkeletonModel;

class Escola extends AbstractSkeletonModel
{
    protected $table = "escola";
    public $timestamps = false;
    protected $guarded = [];

}
