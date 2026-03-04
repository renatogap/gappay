<?php

namespace App\Models\Seguranca;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use GapPay\Seguranca\Models\Entity\SegPerfil;

class VwMenu extends Model
{
    protected $table = 'vw_menu';

    public function perfis(): BelongsToMany
    {
        return $this->belongsToMany(SegPerfil::class);
    }
}
