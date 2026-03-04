<?php

namespace GapPay\Seguranca\database\factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use GapPay\Seguranca\Models\Entity\SegPerfil;

class SegPerfilFactory extends Factory
{
    protected $model = SegPerfil::class;
    public function definition(): array
    {
        return [
            'nome' => $this->faker->name,
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
