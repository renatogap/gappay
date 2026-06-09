<?php

namespace App\Models\Entity;

use GapPay\Seguranca\Models\Entity\AbstractSkeletonModel;

class CartaoCliente extends AbstractSkeletonModel
{
    protected $table = "cartao_cliente";
    //public $timestamps = false;
    protected $guarded = [];

   

    public function situacao()
    {
        return $this->belongsTo(\App\Models\Entity\SituacaoCartao::class, 'status', 'id');
    }

    public function responsavel(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Responsavel::class, 'responsavel_id');
    }

    public function cartao()
    {
        return $this->belongsTo(Cartao::class, 'fk_cartao', 'id');
    }

}
