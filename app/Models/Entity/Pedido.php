<?php

namespace App\Models\Entity;

use GapPay\Seguranca\Models\Entity\AbstractSkeletonModel;

class Pedido extends AbstractSkeletonModel
{
    protected $table = "pedido";
    protected $guarded = [];

    public function itens()
    {
        return $this->hasMany(\App\Models\Entity\PedidoItem::class, 'fk_pedido', 'id');
    }
}
