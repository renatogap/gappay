<?php

namespace GapPay\Seguranca\Models\Entity;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SegHistorico extends Model
{
    use HasFactory;

    protected $table = 'seg_historico';
    protected $guarded = [];

    protected $casts = [
        'antes' => 'array',
        'depois' => 'array'
    ];
}
