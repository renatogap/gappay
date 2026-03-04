<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use PHPUnit\Framework\Attributes\Test;
use GapPay\Seguranca\Models\Entity\Usuario;
use GapPay\Seguranca\Models\Regras\DB;
use Tests\TestCase;

class MenuTest extends TestCase
{
    #[Test]
    public function menu_usuario_deslogado(): void
    {
        $response = $this->get('/api/usuario/info');
        $response->assertStatus(401);
    }

    #[Test]
    public function menu_usuario_1()
    {
        $response = $this->actingAs(static::$usuario)->get('/api/usuario/info');
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'nome',
            'email',
            'rotas',
            'menus',
            'cadastro_incompleto',
        ]);
    }

}
