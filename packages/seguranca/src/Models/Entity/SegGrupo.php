<?php

namespace GapPay\Seguranca\Models\Entity;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use GapPay\Seguranca\database\factories\SegGrupoFactory;

/**
 * @property int $id
 * @property int $usuario_id
 * @property int $perfil_id
 * @property string $created_at
 * @property string $updated_at
 *
 */
class SegGrupo extends AbstractSkeletonModel
{
    use HasFactory;

    protected $table = 'seg_grupo';
    protected $guarded = [];

    protected static function newFactory(): SegGrupoFactory
    {
        return SegGrupoFactory::new();
    }
}
