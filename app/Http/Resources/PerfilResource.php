<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use GapPay\Seguranca\Models\Regras\PerfilRegras;

class PerfilResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $acoes = PerfilRegras::gridPerfil($this['usuario']);
        $grupos = array_map(fn ($item) => $item['nome'], $acoes);
        $grupos[] = 'Todos';
        return [
            'destaque' => $acoes,
            'grupos' => $grupos,
        ];
    }
}
