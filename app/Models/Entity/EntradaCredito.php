<?php

namespace App\Models\Entity;

use GapPay\Seguranca\Models\Entity\AbstractSkeletonModel;

class EntradaCredito extends AbstractSkeletonModel
{
    protected $table = "entrada_credito";
    public $timestamps = false;
    protected $guarded = [];

    public function tipoPagamento()
    {
        return $this->belongsTo(TipoPagamento::class, 'fk_tipo_pagamento', 'id');
    }
}
