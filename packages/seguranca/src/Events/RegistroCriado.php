<?php

namespace GapPay\Seguranca\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class RegistroCriado
{
    use Dispatchable, SerializesModels;

    public $objeto;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($objeto)
    {
        $this->objeto = $objeto;
    }

}
