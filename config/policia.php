<?php
return [
    'nome' => env('NOME_SISTEMA', 'GapPay'), //nome do sistema
    'codigo' => 1, //Código deste sistema na tabela de produção "sistema" no schema segurança. Não deixe como 35 no seu projeto
    'url_front' => env('URL_FRONT'),
    'expiracao_login' => 180, //tempo em dias para usuário perder acesso ao sistema por falta de uso
    'registrar_log' => env('SEGURANCA_REGISTRAR_LOG', false), //habilita ou não o registro de log nos models (use apenas para teste)
    'controle_acesso' => env('SEGURANCA_CONTROLE_ACESSO', true), //Se falso os usuários ficam com permissão total no sistema
    'dashboard' => '/home',
    //Esta chave permite ler o cookie criptografado de autenticação única.
    //Alterar a chave abaixo significa quebrar a autenticação única neste projeto
    'chave_sso' => env('SEGURANCA_CHAVE_SSO', ''),
    'desenvolvido_por' => env('DESENVOLVIDO_POR', 'Rentec Digital'),


    'favicon' => env('APP_FAVICON', 'images/favicon.ico'),
    'logo' => env('APP_LOGO', 'images/logo-parque.png'),
    'logo_sistema' => env('APP_LOGO', 'images/logo-sistema.png'),

    'limite_devolucao' => 70,
    'valor_cartao' => '10.00',

    'valor_mensalidade' => '50.00',
    'valor_mensalidade_com_dependente' => '75.00',
    'limite_dependentes_sem_custo' => 3,
    'valor_dependente_adicional' => '15.00',
    'limite_dias_em_atraso' => 5,

    'background' => '#195287ff !important',
    'btn-parque' => '#003163ff !important',
    'btn-parque-hover' => '#011643ff !important',
    'btn-secondary' => '#e3e0e0 !important'
];
