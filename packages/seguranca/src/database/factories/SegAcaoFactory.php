<?php

namespace GapPay\Seguranca\database\factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use GapPay\Seguranca\Models\Entity\SegAcao;

class SegAcaoFactory extends Factory
{

    protected $model = SegAcao::class;
    public function definition(): array
    {
        return [
            'nome' => $this->faker->url(),
            'method' => 'GET',
            'descricao' => $this->faker->sentence(),
            'destaque' => false,
            'nome_amigavel' => $this->faker->userName(),
            'grupo' => 'GeradoAutomaticamente',
            'obrigatorio' => false,
            'log_acesso' => false
        ];
    }

    public function tela(): static
    {
        return $this->state(function (array $attributes) {
            return [
                'destaque' => true,
                'nome_amigavel' => $this->faker->userName(),
            ];
        });
    }
}
