<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Collection;

class ClientePedidoMail extends Mailable
{
    use Queueable, SerializesModels;

    public Collection $pedidos;
    public string $qrCodePath;

    /**
     * Create a new message instance.
     */
    public function __construct(Collection $pedidos, string $qrCodePath)
    {
        $this->pedidos = $pedidos;
        $this->qrCodePath  = $qrCodePath;
    }

    public function build()
    {
        return $this->subject('GapPay - Comprovante de Pedido #' . str_pad($this->pedidos[0]->id, 6, '0', STR_PAD_LEFT))
            ->view('emails.cliente-pedido')
            ->with([
                'pedidos' => $this->pedidos,
                'qrCodePath'  => $this->qrCodePath,
            ]);
    }
}