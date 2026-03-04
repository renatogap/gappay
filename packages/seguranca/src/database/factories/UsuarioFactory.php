<?php

namespace GapPay\Seguranca\database\factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use GapPay\Seguranca\Models\Entity\SegGrupo;
use GapPay\Seguranca\Models\Entity\Usuario;
use GapPay\Seguranca\Models\Entity\UsuarioSistema;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<Usuario>
 */
class UsuarioFactory extends Factory
{
    protected $model = Usuario::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $senha = Hash::make('policiateste');
        return [
            'nome' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'senha' => null,
            'senha2' => $senha,
            'dt_cadastro' => date('Y-m-d H:i:s'),
            'excluido' => false,
            'primeiro_acesso' => false,
            'cpf' => fake()->unique()->numerify('###########'),
            'nascimento' => fake()->date(),
            'remember_token' => null,
            'diretor' => false,
            'fk_unidade' => 9,
        ];
    }

    /**
     * Indica que o usuário é um administrador
     *
     * @return static
     */
    public function admin(): static {
        return $this->afterCreating(function (Usuario $usuario) {
            SegGrupo::factory()->create([
                'usuario_id' => $usuario->id,
                'perfil_id' => 1,
            ]);
        });
    }

    /**
     * Indica que o usuário está ativo neste sistema
     *
     * @return static
     */
    public function habilitado(): static
    {
        return $this->afterCreating(function (Usuario $usuario) {
            UsuarioSistema::factory()->create([
                'usuario_id' => $usuario->id,
                'status' => 1,
            ]);
        });
    }

    /**
     * Indica que o usuário está desativado neste sistema
     *
     * @return static
     */
    public function desabilitado(): static
    {
        return $this->afterCreating(function (Usuario $usuario) {
            UsuarioSistema::factory()->create([
                'usuario_id' => $usuario->id,
                'status' => 0,
            ]);
        });
    }
}
