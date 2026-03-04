<?php

namespace GapPay\Seguranca\Models\Entity;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use GapPay\Seguranca\database\factories\LinkTemporarioFactory;

/**
 * @property int id
 * @property int $fk_usuario
 * @property string $data_expiracao
 * @property bool $usado
 * @property string $hash
 * @property string $data_gerado
 */
class LinkTemporario extends Model
{
    use HasFactory;

    // protected $connection = 'conexao_seguranca';
    protected $table = 'link_temporario';
    protected $guarded = [];
    public $timestamps = false;

    protected static function newFactory(): LinkTemporarioFactory
    {
        return LinkTemporarioFactory::new();
    }
}
