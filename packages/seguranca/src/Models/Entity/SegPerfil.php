<?php

namespace GapPay\Seguranca\Models\Entity;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use GapPay\Seguranca\database\factories\SegPerfilFactory;

/**
 * @property int $id
 * @property int $nome
 * @property int $created_at
 * @property int $updated_at
 *
 * @method static self perfisNaoRoot() Todos os perfis que não são root
 */
class SegPerfil extends AbstractSkeletonModel
{
    use HasFactory;

    protected $table = 'seg_perfil';
    protected $guarded = [];

    protected static function newFactory(): SegPerfilFactory
    {
        return SegPerfilFactory::new();
    }
}
