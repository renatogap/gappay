<?php

namespace GapPay\Seguranca\database\factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use GapPay\Seguranca\Models\Entity\UsuarioSistema;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<UsuarioSistema>
 */
class UsuarioSistemaFactory extends Factory
{
    protected $model = UsuarioSistema::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'usuario_id' => 1,
            'sistema_id' => config('policia.codigo'),
            'ultimo_acesso' => date('Y-m-d H:i:s'),
            'status' => 1,
            'fk_usuario_cadastro' => 1,
        ];
    }
}
