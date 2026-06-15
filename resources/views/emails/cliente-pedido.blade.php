<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Comprovante de Pedido – GapPay</title>
  <style>
    @import url('https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600&family=Sora:wght@700;800&display=swap');

    * { margin: 0; padding: 0; box-sizing: border-box; }

    body {
      background-color: #0d1b2a;
      font-family: 'DM Sans', sans-serif;
      color: #e8edf2;
      -webkit-font-smoothing: antialiased;
    }

    .wrapper {
      max-width: 560px;
      margin: 48px auto;
      border-radius: 16px;
      overflow: hidden;
      box-shadow: 0 24px 64px rgba(0,0,0,0.5);
    }

    /* ── HEADER ── */
    .header {
      background: linear-gradient(135deg, #1a3a5c 0%, #0f2540 100%);
      padding: 36px 40px 28px;
      position: relative;
      overflow: hidden;
    }

    .header::before {
      content: '';
      position: absolute;
      top: -40px; right: -40px;
      width: 180px; height: 180px;
      border-radius: 50%;
      background: rgba(255,255,255,0.04);
    }

    .header::after {
      content: '';
      position: absolute;
      bottom: -60px; left: 30px;
      width: 240px; height: 240px;
      border-radius: 50%;
      background: rgba(255,255,255,0.03);
    }

    .logo {
      font-family: 'Sora', sans-serif;
      font-size: 26px;
      font-weight: 800;
      color: #ffffff;
      letter-spacing: -0.5px;
    }

    .logo span { color: #4fc3f7; }

    /* ── BODY ── */
    .body {
      background: #132236;
      padding: 40px 40px 36px;
    }

    .greeting {
      font-size: 22px;
      font-weight: 600;
      color: #ffffff;
      margin-bottom: 8px;
    }

    .intro {
      font-size: 15px;
      color: rgba(232,237,242,0.75);
      line-height: 1.7;
      margin-bottom: 32px;
    }

    /* ── TICKET (mesmo nome da página: ticket-comprovante) ── */
    .ticket-comprovante {
      background: #dbf3ff;
      border-radius: 12px;
      overflow: hidden;
      margin-bottom: 28px;
      position: relative;
    }

    /* Perfuração topo/base (substitui o ::before/::after da página) */
    .ticket-perfuracao-top,
    .ticket-perfuracao-bottom {
      height: 14px;
      background: repeating-linear-gradient(
        90deg,
        transparent,
        transparent 8px,
        #132236 8px,
        #132236 16px
      );
    }

    /* ticket-conteudo: mesmo nome da página */
    .ticket-conteudo {
      padding: 24px 28px;
    }

    /* ticket-logo: mesmo nome da página */
    .ticket-logo {
      text-align: center;
      font-size: 16px;
      font-weight: 700;
      color: #0f2540;
      margin-bottom: 4px;
    }

    /* ticket-numero-pedido: mesmo nome da página */
    .ticket-numero-pedido {
      font-size: 13px;
      font-weight: 700;
      color: #3153e7;
      letter-spacing: 1.5px;
      text-align: center;
      margin-top: 4px;
    }

    /* ticket-data: mesmo nome da página */
    .ticket-data {
      font-size: 12px;
      color: #777;
      text-align: center;
      margin-top: 4px;
    }

    /* ticket-divisor: mesmo nome da página */
    .ticket-divisor {
      border: none;
      border-top: 2px dashed #aac8d8;
      margin: 16px 0;
    }

    /* ticket-itens: mesmo nome da página */
    .ticket-itens {
      width: 100%;
      border-collapse: collapse;
      margin: 1.5em 0;
      border-top: 1px dotted #999;
    }

    /* ticket-item-linha: mesmo nome da página */
    .ticket-item-linha td {
      padding: 0.6em 0;
      border-bottom: 1px dotted #999;
      font-size: 0.85em;
    }

    /* ticket-item-nome: mesmo nome da página */
    .ticket-item-nome {
      font-weight: 600;
      color: #333;
      width: 100%;
    }

    /* ticket-item-valor: mesmo nome da página */
    .ticket-item-valor {
      white-space: nowrap;
      font-weight: 600;
      padding-left: 12px !important;
      color: #333;
      text-align: right;
    }

    .ticket-obs {
      font-size: 0.75em;
      color: #ff9800;
      padding: 2px 0 6px;
    }

    /* ticket-totais: mesmo nome da página */
    .ticket-totais {
      margin-top: 1.5em;
    }

    /* ticket-total-linha: mesmo nome da página */
    .ticket-total-linha td {
      font-size: 0.95em;
      color: #555;
      padding: 3px 0;
    }

    /* ticket-total-final: mesmo nome da página */
    .ticket-total-final td {
      font-size: 1.3em;
      font-weight: 700;
      color: #333;
      padding-top: 0.8em;
    }

    /* ticket-qrcode: mesmo nome da página */
    .ticket-qrcode {
      text-align: center;
      margin: 1.5em 0;
      padding: 1.5em 0;
    }

    /* ticket-qrcode-img: mesmo nome da página */
    .ticket-qrcode-img {
      width: 160px;
      height: 160px;
      display: block;
      margin: 0 auto 0.8em;
    }

    /* ticket-qrcode-label: mesmo nome da página */
    .ticket-qrcode-label {
      font-size: 0.8em;
      color: #999;
      text-transform: uppercase;
      letter-spacing: 0.5px;
      font-weight: 600;
    }

    /* ticket-rodape: mesmo nome da página */
    .ticket-rodape {
      text-align: center;
      font-size: 0.8em;
      color: #999;
      margin-top: 1em;
      padding-top: 1em;
      border-top: 1px dotted #999;
    }

    /* ── NOTE ── */
    .note {
      background: rgba(79,195,247,0.07);
      border-left: 3px solid #4fc3f7;
      border-radius: 0 8px 8px 0;
      padding: 14px 18px;
      font-size: 14px;
      color: rgba(232,237,242,0.7);
      line-height: 1.6;
    }

    /* ── FOOTER ── */
    .footer {
      background: #0d1b2a;
      padding: 24px 40px;
      border-top: 1px solid rgba(255,255,255,0.06);
    }

    .sign {
      font-size: 14px;
      color: rgba(232,237,242,0.5);
      line-height: 1.6;
    }

    .sign strong { color: rgba(232,237,242,0.85); }

    .divider {
      height: 1px;
      background: rgba(255,255,255,0.06);
      margin: 18px 0;
    }

    .legal {
      font-size: 11px;
      color: rgba(232,237,242,0.28);
      line-height: 1.6;
    }

    @media (max-width: 600px) {
      .wrapper { margin: 0; border-radius: 0; }
      .header, .body, .footer { padding-left: 24px; padding-right: 24px; }
      .ticket-conteudo { padding: 1.5em; }
      .ticket-numero-pedido { font-size: 1.1em; }
      .ticket-item-linha td { font-size: 0.8em; }
    }
  </style>
</head>
<body>

  <div class="wrapper">

    <div class="header">
      <div class="logo">Gap<span>Pay</span></div>
    </div>

    <div class="body">

      <p class="greeting">Novo pedido recebido!</p>
      <p class="intro">
        Pedido realizado. Confira abaixo o comprovante completo.
      </p>

      <div class="ticket-comprovante">
        {{-- <div class="ticket-perfuracao-top"></div> --}}

        <div class="ticket-conteudo">

          <div class="ticket-logo">{{ $pedidos[0]->nome_cliente }}</div>

          <div class="ticket-numero-pedido">
            Pedido #{{ str_pad($pedidos[0]->id, 6, '0', STR_PAD_LEFT) }}
          </div>

          <div class="ticket-data">
            {{ date('d/m/Y H:i', strtotime($pedidos[0]->dt_pedido)) }}h
          </div>

          <hr class="ticket-divisor">

          <table class="ticket-itens" cellpadding="0" cellspacing="0">
            @foreach($pedidos as $pedido)
              <tr class="ticket-item-linha">
                <td class="ticket-item-nome">
                  {{ $pedido->unid == 1 ? intval($pedido->quantidade) : $pedido->quantidade }}x {{ $pedido->nome_item }}
                </td>
                <td class="ticket-item-valor">
                  R$ {{ number_format($pedido->valor_total_item, 2, ',', '.') }}
                </td>
              </tr>
              @if($pedido->observacao)
                <tr>
                  <td colspan="2" class="ticket-obs">* {{ $pedido->observacao }}</td>
                </tr>
              @endif
            @endforeach
          </table>

          @php
            $subtotal = $pedidos->where('status', '!=', 4)->sum('valor_total_item');
            $taxa = $pedidos[0]->taxa_servico ?? 0;
          @endphp

          <table class="ticket-totais" width="100%" cellpadding="0" cellspacing="0">
            @if($taxa > 0)
              <tr class="ticket-total-linha">
                <td>Subtotal:</td>
                <td style="text-align:right;">R$ {{ number_format($subtotal - $taxa, 2, ',', '.') }}</td>
              </tr>
              <tr class="ticket-total-linha">
                <td>Taxa de Serviço:</td>
                <td style="text-align:right;">R$ {{ number_format($taxa, 2, ',', '.') }}</td>
              </tr>
            @endif
            {{-- <tr>
              <td colspan="2" style="border-top: 1px dotted #999; padding-top: 0;"></td>
            </tr> --}}
            <tr class="ticket-total-final">
              <td>TOTAL:</td>
              <td style="text-align:right;">R$ {{ number_format($subtotal, 2, ',', '.') }}</td>
            </tr>
          </table>

          <div class="ticket-qrcode">
            <img src="{{ $message->embedData(file_get_contents($qrCodePath), 'qrcode.png', 'image/png') }}"
                 class="ticket-qrcode-img"
                 alt="QR Code para confirmar entrega"
                 width="160"
                 height="160">
            <div class="ticket-qrcode-label">Escaneie para receber o pedido</div>
          </div>

          <div class="ticket-rodape">
            Obrigado pela sua compra!<br>
            {{ config('app.NOME_SISTEMA') }}
          </div>

        </div>

        <div class="ticket-perfuracao-bottom"></div>
      </div>

      <div class="note">
        Este e-mail foi gerado automaticamente após a realização do pedido.
        Caso haja alguma inconsistência, verifique diretamente no sistema.
      </div>

    </div>

    <div class="footer">
      <p class="sign">
        Atenciosamente,<br>
        <strong>Equipe GapPay</strong>
      </p>
      <div class="divider"></div>
      <p class="legal">
        Este é um e-mail automático, por favor não responda diretamente a esta mensagem.<br>
        © {{ date('Y') }} {{ config('app.NOME_SISTEMA') }}. Todos os direitos reservados.
      </p>
    </div>

  </div>

</body>
</html>