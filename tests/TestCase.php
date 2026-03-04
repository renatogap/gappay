<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use GapPay\Seguranca\Models\Entity\Usuario;

abstract class TestCase extends BaseTestCase
{
    protected static Usuario $usuario;

    protected function setUp(): void
    {
        parent::setUp();

        if (!isset(static::$usuario)) {
            static::$usuario = Usuario::find(1);
        }
    }
}
