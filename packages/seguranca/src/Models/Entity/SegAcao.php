<?php

namespace GapPay\Seguranca\Models\Entity;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use GapPay\Seguranca\database\factories\SegAcaoFactory;

/**
 * @property int $id
 * @property string $nome
 * @property string $method
 * @property string $descricao
 * @property string $destaque
 * @property string $nome_amigavel
 * @property bool $obrigatorio
 * @property string $grupo
 * @property bool $log_acesso
 * @property string $created_at
 * @property string $updated_at
 */
class SegAcao extends Model
{
    use HasFactory;

    protected $table = 'seg_acao';
    protected $guarded = [];

    protected static function newFactory(): SegAcaoFactory
    {
        return SegAcaoFactory::new();
    }

    public function dependencias(): BelongsToMany
    {
        return $this->belongsToMany(
            SegAcao::class, //Model relacionado (auto-relacionamento)
            SegDependencia::class,// Tabela pivot
            'acao_atual_id',// Chave estrangeira da tabela atual
            'acao_dependencia_id'// Chave estrangeira da tabela relacionada
        );
    }

    public function perfis(): BelongsToMany
    {
        return $this->belongsToMany(
            SegPerfil::class,
            SegPermissao::class,
            'acao_id',
            'perfil_id'
        );
    }
}
