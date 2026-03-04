<?php

namespace GapPay\Seguranca\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NovoUsuarioMail extends Mailable
{
    use Queueable, SerializesModels;

    public array $info;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(array $info)
    {
        $this->info = $info;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build(): static
    {
        return $this->view('usuario.novo-email', $this->info);
    }
}
