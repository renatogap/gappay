<?php

namespace GapPay\Seguranca\Models\Regras;

use GapPay\Seguranca\Models\Form\Form;

class UsuarioForm extends Form
{
    public function __construct(
        public ?int $id = null,
        public ?string $nome = null,
        public ?string $email = null,
        public ?string $cpf = null,
        public ?string $nascimento = null,
        public ?string $senha = null,
        public ?array $perfil = [],
        public ?array $cardapio = [],
        public ?bool $notificarUsuario = false,
    ) {
    }

    public function setNome(?string $value): void
    {
        $this->nome = trim($value);
    }

    public function setEmail(?string $value): void
    {
        $this->email = trim(preg_replace('/\s{2,}/', '', mb_strtolower($value, 'UTF-8')));
    }

    public function setCpf(?string $value): void
    {
        $this->cpf = preg_replace('/\D/', null, $value);
    }

    /**
     * @return bool
     */
    public function isNotificarUsuario(): bool
    {
        return $this->notificarUsuario;
    }
}
