<?php

namespace GapPay\Seguranca\database\factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use GapPay\Seguranca\Models\Entity\LinkTemporario;

class LinkTemporarioFactory extends Factory
{
    protected $model = LinkTemporario::class;
    public function definition(): array
    {
        return [
            'fk_usuario' => 1,
            'data_expiracao' => date('Y-m-d H:i:s', strtotime('+1 day')),
            'usado' => false,
            'hash' => Str::uuid(),
            'data_gerado' => date('Y-m-d H:i:s'),
        ];
    }
}
