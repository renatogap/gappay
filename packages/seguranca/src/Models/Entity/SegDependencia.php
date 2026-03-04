<?php

namespace GapPay\Seguranca\Models\Entity;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int id
 * @property int $acao_atual_id
 * @property int $acao_dependencia_id
 * @property string created_at
 * @property string updated_at
 */
class SegDependencia extends Model
{

    protected $table = 'seg_dependencia';
    protected $guarded = [];

}
