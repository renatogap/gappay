<?php

namespace GapPay\Seguranca\Models\Entity;

use Illuminate\Database\Eloquent\Model;

/**
 * @property string $id
 * @property int $fk_usuario
 * @property string $ip
 * @property string $login
 * @property string $logout
 * @property string $user_agent
 * @property string $ultimo_acesso
 * @property string $session_id
 * @property int $fk_sistema_login
 * @property int $fk_sistema_logout
 */
class Acesso extends Model
{
    // protected $connection = 'conexao_seguranca';
    protected $table = 'acesso';
    protected $guarded = [];
    public $timestamps = false;
}
