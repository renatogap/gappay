<?php

namespace App\Mail;

use App\Models\Entity\Responsavel;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ResponsavelTokenMail extends Mailable
{
    use Queueable, SerializesModels;

    public Responsavel $responsavel;

    public function __construct(Responsavel $responsavel)
    {
        $this->responsavel = $responsavel;
    }

    public function build()
    {
        if($this->responsavel->recuperarSenha){
            return $this->subject('Recuperar Senha - Gappay')
                ->view('emails.responsavel-recuperar-senha')
                ->with([
                    'token' => $this->responsavel->token,
                    'email' => $this->responsavel->email,
                ]);
        } else {
            return $this->subject('Seu token de validação Gappay')
                ->view('emails.responsavel-token')
                ->with([
                    'token' => $this->responsavel->token,
                    'email' => $this->responsavel->email,
                ]);
        }
    }
}
