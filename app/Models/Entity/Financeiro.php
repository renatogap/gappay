<?php

namespace App\Models\Entity;

use Illuminate\Database\Eloquent\SoftDeletes;
use GapPay\Seguranca\Models\Entity\AbstractSkeletonModel;

class Financeiro extends AbstractSkeletonModel
{
    protected $table = "cobranca";
    public $timestamps = false;
    protected $guarded = [];
    use SoftDeletes;


    public function cliente()
    {
        return $this->belongsTo(\App\Models\Entity\Cliente::class, 'fk_cliente', 'id');
    }

}
