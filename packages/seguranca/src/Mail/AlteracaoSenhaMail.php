<?php

namespace GapPay\Seguranca\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class AlteracaoSenhaMail extends Mailable
{
    use Queueable, SerializesModels;

    public array $info;

    public function __construct(array $info)
    {
        $this->info = $info;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Alteração de Senha',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'usuario.alteracao-senha-email',
        );
    }
}
