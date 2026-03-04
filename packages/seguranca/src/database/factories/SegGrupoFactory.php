<?php

namespace GapPay\Seguranca\database\factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use GapPay\Seguranca\Models\Entity\SegGrupo;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<SegGrupo>
 */
class SegGrupoFactory extends Factory
{
    protected $model = SegGrupo::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'usuario_id' => 1,
            'perfil_id' => 1,
        ];
    }
}
