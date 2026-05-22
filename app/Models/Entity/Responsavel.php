<?php

namespace App\Models\Entity;

use GapPay\Seguranca\Models\Entity\AbstractSkeletonModel;

class Responsavel extends AbstractSkeletonModel
{
    protected $table = 'responsavel';
    protected $guarded = [];

    protected $casts = [
        'validado' => 'boolean',
        'concordo' => 'boolean',
    ];

   public function alunos(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(CartaoCliente::class, 'responsavel_id');
    }
}
