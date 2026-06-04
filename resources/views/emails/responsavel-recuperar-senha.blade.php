<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Recuperar senha – GapPay</title>
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

    .logo span {
      color: #4fc3f7;
    }

    .tagline {
      font-size: 12px;
      color: rgba(255,255,255,0.45);
      letter-spacing: 1.5px;
      text-transform: uppercase;
      margin-top: 4px;
    }

    .body {
      background: #132236;
      padding: 40px 40px 36px;
    }

    .greeting {
      font-size: 22px;
      font-weight: 600;
      color: #ffffff;
      margin-bottom: 12px;
    }

    .intro {
      font-size: 15px;
      color: rgba(232,237,242,0.75);
      line-height: 1.7;
      margin-bottom: 32px;
    }

    .token-box {
      background: linear-gradient(135deg, #1a3a5c, #0f2540);
      border: 1px solid rgba(79,195,247,0.25);
      border-radius: 12px;
      padding: 28px 32px;
      text-align: center;
      margin-bottom: 32px;
      position: relative;
      overflow: hidden;
    }

    .token-box::before {
      content: '';
      position: absolute;
      inset: 0;
      background: radial-gradient(circle at 50% 0%, rgba(79,195,247,0.08) 0%, transparent 70%);
    }

    .token-label {
      font-size: 11px;
      letter-spacing: 2px;
      text-transform: uppercase;
      color: rgba(79,195,247,0.7);
      margin-bottom: 14px;
    }

    .token-value {
      font-family: 'Sora', sans-serif;
      font-size: 38px;
      font-weight: 800;
      color: #ffffff;
      letter-spacing: 10px;
      text-shadow: 0 0 24px rgba(79,195,247,0.4);
      user-select: text;
      position: relative;
      z-index: 1;
    }

    .token-exp {
      font-size: 12px;
      color: rgba(232,237,242,0.4);
      margin-top: 12px;
    }

    .note {
      background: rgba(79,195,247,0.07);
      border-left: 3px solid #4fc3f7;
      border-radius: 0 8px 8px 0;
      padding: 14px 18px;
      font-size: 14px;
      color: rgba(232,237,242,0.7);
      line-height: 1.6;
      margin-bottom: 32px;
    }

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

    .sign strong {
      color: rgba(232,237,242,0.85);
    }

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
      .token-value { font-size: 28px; letter-spacing: 6px; }
    }
  </style>
</head>
<body>

  <div class="wrapper">

    <div class="header">
      <div class="logo">Gap<span>Pay</span></div>
    </div>

    <div class="body">

      <p class="greeting">Olá!</p>
      <p class="intro">
        Para recuperar sua senha no <strong>GapPay</strong>, utilize o código de validação
        abaixo no formulário de confirmação de e-mail.
      </p>

      <!-- Token -->
      <div class="token-box">
        <div class="token-label">Seu código de validação</div>
        <div class="token-value">{{ $token }}</div>
        <div class="token-exp">Este código é pessoal e intransferível.</div>
      </div>

      <div class="note">
        Se você não solicitou recuperação de senha, ignore este e-mail. Nenhuma ação será necessária
        e sua conta não será criada.
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
        © {{ date('Y') }} GapPay. Todos os direitos reservados.
      </p>
    </div>

  </div>

</body>
</html>