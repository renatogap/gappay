<?php

namespace GapPay\Seguranca\Models\Regras;

class Formulario
{
    protected array $dados;

    public function __construct(array $dados = [])
    {
        $this->dados = $dados;
    }

    public function __get($nome): ?string
    {
        return $this->dados[$nome] ?? null;
    }
}
