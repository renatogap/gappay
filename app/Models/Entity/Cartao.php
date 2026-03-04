<?php

namespace App\Models\Entity;

use GapPay\Seguranca\Models\Entity\AbstractSkeletonModel;

class Cartao extends AbstractSkeletonModel
{
    protected $table = "cartao";
    public $timestamps = false;
    protected $guarded = [];

    public function situacao()
    {
        return $this->belongsTo(\App\Models\Entity\SituacaoCartao::class, 'fk_situacao', 'id');
    }

}
