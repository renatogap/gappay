<?php

namespace GapPay\Seguranca\Models\Entity;

/**
 * @property int $id
 * @property int $usuario_id
 * @property int $perfil_id
 * @property string $created_at
 * @property string $updated_at
 *
 */
class SegPermissao extends AbstractSkeletonModel
{
    protected $table = 'seg_permissao';
    protected $guarded = [];
}
