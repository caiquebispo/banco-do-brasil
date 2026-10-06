<?php

return [
    'environment' => env('BB_ENVIRONMENT', 'sandbox'),

    'contas' => [
        'principal' => [
            'client_id' => env('BB_CLIENT_ID'),
            'client_secret' => env('BB_CLIENT_SECRET'),
            'developer_application_key' => env('BB_DEVELOPER_APP_KEY'),
            'convenio' => env('BB_CONVENIO'),
            'carteira' => env('BB_CARTEIRA', 17),
            'variacao_carteira' => env('BB_VARIACAO_CARTEIRA', 35),
            'chave_pix' => env('BB_CHAVE_PIX'),
        ],
    ],

    'default' => env('BB_DEFAULT_CONTA', 'principal'),

    'timeout' => (int) env('BB_TIMEOUT', 30),

    'retry' => [
        'times' => (int) env('BB_RETRY_TIMES', 3),
        'sleep' => (int) env('BB_RETRY_SLEEP', 500),
    ],

    'certificado' => [
        'path' => env('BB_CERTIFICADO_PATH'),
        'senha' => env('BB_CERTIFICADO_SENHA'),
    ],

    'webhook' => [
        'secret' => env('BB_WEBHOOK_SECRET'),
    ],
];
