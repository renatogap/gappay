<?php

namespace GapPay\Seguranca\Models\Form;

class PerfilForm extends Form
{
    public function __construct(
        public ?int $id = null,
        public ?string $nome = null,
        public ?array $permissoes = null,
    )
    {
        $this->permissoes = $this->permissoes ?? [];
    }
}