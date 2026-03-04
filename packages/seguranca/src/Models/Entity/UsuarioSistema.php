<?php

namespace GapPay\Seguranca\Models\Entity;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use GapPay\Seguranca\database\factories\UsuarioSistemaFactory;

/**
 * @property int $sistema_id
 * @property int $usuario_id
 * @property int $id
 * @property string $ultimo_acesso
 * @property int $status
 * @property string created_at
 * @property string updated_at
 * @property int $fk_usuario_cadastro
 * @property int $fk_usuario_edicao
 */
class UsuarioSistema extends Model
{
    use HasFactory;

    // protected $connection = 'conexao_seguranca';
    protected $table = 'usuario_sistema';
    public $timestamps = false;
    protected $guarded = [];

    protected static function newFactory(): UsuarioSistemaFactory
    {
        return UsuarioSistemaFactory::new();
    }
}
