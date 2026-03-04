<?php

namespace GapPay\Seguranca\Models\Form;

class AcaoForm extends Form
{
    public function __construct(
        public ?string $nome = null,
        public ?int    $id = null,
        public ?string $descricao = null,
        public ?bool   $destaque = false,
        public ?string $method = 'GET',
        public ?bool   $log_acesso = false,
        public ?bool   $obrigatorio = false,
        public ?string $nome_amigavel = null,
        public ?string $grupo = 'GeradoAutomaticamente',
        public ?array  $dependencia = [],
    )
    {
        // Quando chegar outra coisa diferente dos valores aceitáveis, então corrige o valor
        $this->dependencia = !is_array($this->dependencia) ? [] : $this->dependencia;
        $this->method = !in_array($this->method, ['GET', 'POST', 'PUT,PATCH', 'DELETE']) ? 'GET' : $this->method;
        $this->destaque = boolval($this->destaque);
        $this->log_acesso = boolval($this->log_acesso);
        $this->obrigatorio = boolval($this->obrigatorio);
    }

    public function isObrigatorio(): bool
    {
        return $this->obrigatorio;
    }

    public function isDestaque(): bool
    {
        return $this->destaque;
    }

    public function isLogAcesso(): bool
    {
        return $this->log_acesso;
    }

    public function isRotaFront(): bool
    {
        return $this->isDestaque() && !str_starts_with('api/', $this->nome);
    }
}
