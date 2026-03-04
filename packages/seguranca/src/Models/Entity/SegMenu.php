<?php

namespace GapPay\Seguranca\Models\Entity;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use GapPay\Seguranca\Models\Facade\UsuarioDB;
use GapPay\Seguranca\Models\Regras\UsuarioLogado;

class SegMenu extends Model
{
    use HasFactory;
    protected $table = 'seg_menu';
    protected $guarded = [];

    public function scopeAtivo(Builder $query): Builder
    {
        return $query->where('ativo', true);
    }

//    public function scopeMenusRaiz(Builder $query): Builder
//    {
//        return $query->where('pai', null);
//    }
//
//    public function submenus(): \Illuminate\Database\Eloquent\Relations\HasMany
//    {
//        return $this->hasMany(SegMenu::class, 'pai');
//    }
//
//    public function acao(): \Illuminate\Database\Eloquent\Relations\BelongsTo
//    {
//        return $this->belongsTo(SegAcao::class, 'acao_id');
//    }
}
