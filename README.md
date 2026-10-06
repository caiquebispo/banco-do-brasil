# Banco do Brasil PHP SDK

PHP SDK para as APIs do Banco do Brasil — Cobrança (Boletos), PIX, Pagamentos em Lote e Extratos com suporte multi-conta.

## Requisitos

- PHP 8.2+
- Guzzle 7.0+

## Instalação

```bash
composer require caiquebispo/banco-do-brasil
```

## Configuração

### PHP Puro

```php
use CaiqueBispo\BancoDoBrasil\BancoDoBrasil;

$bb = BancoDoBrasil::make([
    'environment' => 'sandbox', // sandbox, homologacao, producao

    'contas' => [
        'principal' => [
            'client_id'                 => 'seu-client-id',
            'client_secret'             => 'seu-client-secret',
            'developer_application_key' => 'sua-developer-key',
            'convenio'                  => '3128557',
            'carteira'                  => 17,
            'variacao_carteira'         => 35,
            'chave_pix'                 => 'sua-chave-pix@email.com',
        ],
        'filial' => [
            'client_id'                 => 'filial-client-id',
            'client_secret'             => 'filial-client-secret',
            'developer_application_key' => 'filial-developer-key',
            'convenio'                  => '9876543',
            'chave_pix'                 => 'filial@email.com',
        ],
    ],

    'default' => 'principal',
    'timeout' => 30,

    'retry' => [
        'times' => 3,
        'sleep' => 500,
    ],

    // Certificado A1 (obrigatório em produção)
    'certificado' => [
        'path'  => '/caminho/para/certificado.pem',
        'senha' => 'senha-do-certificado',
    ],

    'webhook' => [
        'secret' => 'seu-webhook-secret',
    ],
]);
```

### Laravel

Publique o arquivo de configuração:

```bash
php artisan vendor:publish --tag=banco-do-brasil-config
```

Configure no `.env`:

```env
BB_ENVIRONMENT=sandbox
BB_CLIENT_ID=seu-client-id
BB_CLIENT_SECRET=seu-client-secret
BB_DEVELOPER_APP_KEY=sua-developer-key
BB_CONVENIO=3128557
BB_CARTEIRA=17
BB_VARIACAO_CARTEIRA=35
BB_CHAVE_PIX=sua-chave-pix@email.com
BB_TIMEOUT=30
BB_RETRY_TIMES=3
BB_RETRY_SLEEP=500
BB_CERTIFICADO_PATH=
BB_CERTIFICADO_SENHA=
BB_WEBHOOK_SECRET=
```

O `config/banco-do-brasil.php` gerado:

```php
return [
    'environment' => env('BB_ENVIRONMENT', 'sandbox'),

    'contas' => [
        'principal' => [
            'client_id'                 => env('BB_CLIENT_ID'),
            'client_secret'             => env('BB_CLIENT_SECRET'),
            'developer_application_key' => env('BB_DEVELOPER_APP_KEY'),
            'convenio'                  => env('BB_CONVENIO'),
            'carteira'                  => env('BB_CARTEIRA', 17),
            'variacao_carteira'         => env('BB_VARIACAO_CARTEIRA', 35),
            'chave_pix'                 => env('BB_CHAVE_PIX'),
        ],
    ],

    'default' => env('BB_DEFAULT_CONTA', 'principal'),
    'timeout' => (int) env('BB_TIMEOUT', 30),

    'retry' => [
        'times' => (int) env('BB_RETRY_TIMES', 3),
        'sleep' => (int) env('BB_RETRY_SLEEP', 500),
    ],

    'certificado' => [
        'path'  => env('BB_CERTIFICADO_PATH'),
        'senha' => env('BB_CERTIFICADO_SENHA'),
    ],

    'webhook' => [
        'secret' => env('BB_WEBHOOK_SECRET'),
    ],
];
```

Para multi-conta no Laravel, edite o `config/banco-do-brasil.php`:

```php
'contas' => [
    'principal' => [
        'client_id'                 => env('BB_CLIENT_ID'),
        'client_secret'             => env('BB_CLIENT_SECRET'),
        'developer_application_key' => env('BB_DEVELOPER_APP_KEY'),
        'convenio'                  => env('BB_CONVENIO'),
        'carteira'                  => env('BB_CARTEIRA', 17),
        'variacao_carteira'         => env('BB_VARIACAO_CARTEIRA', 35),
        'chave_pix'                 => env('BB_CHAVE_PIX'),
    ],
    'filial' => [
        'client_id'                 => env('BB_FILIAL_CLIENT_ID'),
        'client_secret'             => env('BB_FILIAL_CLIENT_SECRET'),
        'developer_application_key' => env('BB_FILIAL_DEVELOPER_APP_KEY'),
        'convenio'                  => env('BB_FILIAL_CONVENIO'),
        'chave_pix'                 => env('BB_FILIAL_CHAVE_PIX'),
    ],
],
```

## Multi-Conta

```php
// PHP Puro
$principal = $bb->cobrancas()->listar();
$filial    = $bb->conta('filial')->cobrancas()->listar();

// Laravel
use CaiqueBispo\BancoDoBrasil\Laravel\Facades\BB;

$principal = BB::cobrancas()->listar();
$filial    = BB::conta('filial')->cobrancas()->listar();
```

## Ambientes

```php
use CaiqueBispo\BancoDoBrasil\Enums\Environment;

// Trocar ambiente em runtime
$bb->environment(Environment::Producao)->cobrancas()->listar();

// Laravel
BB::environment(Environment::Homologacao)->cobrancas()->listar();
```

## Cobrança (Boletos)

```php
// Registrar boleto
$boleto = $bb->cobrancas()->registrar([
    'numeroConvenio'             => 3128557,
    'numeroCarteira'             => 17,
    'numeroVariacaoCarteira'     => 35,
    'codigoModalidade'           => 1,
    'dataEmissao'                => '01.01.2025',
    'dataVencimento'             => '31.01.2025',
    'valorOriginal'              => 100.00,
    'codigoAceite'               => 'A',
    'codigoTipoTitulo'           => 2,
    'indicadorPermissaoRecebimentoParcial' => 'N',
    'numeroTituloBeneficiario'   => '123456',
    'pagador' => [
        'tipoInscricao'   => 1,
        'numeroInscricao' => 12345678901,
        'nome'            => 'João da Silva',
        'endereco'        => 'Rua Exemplo, 100',
        'cep'             => 70000000,
        'cidade'          => 'Brasília',
        'bairro'          => 'Centro',
        'uf'              => 'DF',
    ],
]);

// Consultar boleto
$boleto = $bb->cobrancas()->consultar('00031285570000000001');

// Listar boletos
$lista = $bb->cobrancas()->listar([
    'indicadorSituacao' => 'A',
    'agenciaBeneficiario' => 452,
    'contaBeneficiario' => 123456,
]);

// Alterar boleto
$bb->cobrancas()->alterar('00031285570000000001', [
    'dataVencimento' => '28.02.2025',
]);

// Baixar boleto
$bb->cobrancas()->baixar('00031285570000000001');

// Gerar PIX para boleto
$pix = $bb->cobrancas()->gerarPix('00031285570000000001');

// Consultar PIX do boleto
$pix = $bb->cobrancas()->consultarPix('00031285570000000001');

// Cancelar PIX do boleto
$bb->cobrancas()->cancelarPix('00031285570000000001');
```

### Laravel

```php
use CaiqueBispo\BancoDoBrasil\Laravel\Facades\BB;

$boleto = BB::cobrancas()->registrar([...]);
$lista  = BB::cobrancas()->listar(['indicadorSituacao' => 'A']);
$boleto = BB::cobrancas()->consultar('00031285570000000001');
BB::cobrancas()->baixar('00031285570000000001');
```

## PIX — Cobrança Imediata (Cob)

```php
// Criar cobrança sem txid (gerado automaticamente)
$cob = $bb->pixCob()->criar([
    'calendario' => ['expiracao' => 3600],
    'devedor' => [
        'cpf'  => '12345678901',
        'nome' => 'João da Silva',
    ],
    'valor' => ['original' => '100.00'],
    'chave' => 'sua-chave-pix@email.com',
]);

// Criar cobrança com txid
$cob = $bb->pixCob()->criarComTxid('txid-personalizado-123', [
    'calendario' => ['expiracao' => 3600],
    'valor' => ['original' => '50.00'],
    'chave' => 'sua-chave-pix@email.com',
]);

// Consultar cobrança
$cob = $bb->pixCob()->consultar('txid-personalizado-123');

// Revisar cobrança
$bb->pixCob()->revisar('txid-personalizado-123', [
    'valor' => ['original' => '75.00'],
]);

// Listar cobranças
$lista = $bb->pixCob()->listar([
    'inicio' => '2025-01-01T00:00:00Z',
    'fim'    => '2025-12-31T23:59:59Z',
]);
```

## PIX — Cobrança com Vencimento (CobV)

```php
// Criar cobrança com vencimento
$cobv = $bb->pixCobV()->criar('txid-cobv-123', [
    'calendario' => [
        'dataDeVencimento'           => '2025-01-31',
        'validadeAposVencimento'     => 30,
    ],
    'devedor' => [
        'cpf'  => '12345678901',
        'nome' => 'João da Silva',
    ],
    'valor' => ['original' => '200.00'],
    'chave' => 'sua-chave-pix@email.com',
]);

// Consultar
$cobv = $bb->pixCobV()->consultar('txid-cobv-123');

// Revisar
$bb->pixCobV()->revisar('txid-cobv-123', ['status' => 'REMOVIDA_PELO_USUARIO_RECEBEDOR']);

// Listar
$lista = $bb->pixCobV()->listar(['inicio' => '2025-01-01T00:00:00Z', 'fim' => '2025-12-31T23:59:59Z']);
```

## PIX — Lote CobV

```php
// Criar lote
$lote = $bb->pixLoteCobV()->criar('lote-001', [
    'cobsv' => [
        [
            'txid' => 'txid-lote-item-1',
            'calendario' => ['dataDeVencimento' => '2025-01-31'],
            'devedor' => ['cpf' => '12345678901', 'nome' => 'João'],
            'valor' => ['original' => '100.00'],
            'chave' => 'sua-chave@email.com',
        ],
    ],
]);

// Consultar lote
$lote = $bb->pixLoteCobV()->consultar('lote-001');

// Listar lotes
$lista = $bb->pixLoteCobV()->listar();
```

## PIX — Recebidos e Devoluções

```php
// Consultar PIX recebido
$pix = $bb->pix()->consultar('E00000000202401011234abc');

// Listar PIX recebidos
$lista = $bb->pix()->listar([
    'inicio' => '2025-01-01T00:00:00Z',
    'fim'    => '2025-01-31T23:59:59Z',
]);

// Solicitar devolução
$dev = $bb->pix()->devolver('E00000000202401011234abc', 'devolucao-001', [
    'valor' => '10.00',
]);

// Consultar devolução
$dev = $bb->pix()->consultarDevolucao('E00000000202401011234abc', 'devolucao-001');
```

## PIX — Webhook

```php
// Registrar webhook
$bb->pixWebhook()->criar('sua-chave-pix@email.com', [
    'webhookUrl' => 'https://seusite.com/webhook/pix',
]);

// Consultar webhook
$webhook = $bb->pixWebhook()->consultar('sua-chave-pix@email.com');

// Listar webhooks
$lista = $bb->pixWebhook()->listar();

// Excluir webhook
$bb->pixWebhook()->excluir('sua-chave-pix@email.com');
```

## PIX — Location (QR Code)

```php
// Criar location
$loc = $bb->pixLocation()->criar(['tipoCob' => 'cob']);

// Consultar location
$loc = $bb->pixLocation()->consultar(1);

// Listar locations
$lista = $bb->pixLocation()->listar();

// Gerar QR Code
$qr = $bb->pixLocation()->qrcode(1);

// Desvincular txid
$bb->pixLocation()->desvincular(1);
```

## Pagamentos em Lote

```php
// Transferência (TED/PIX)
$transf = $bb->pagamentos()->transferir([
    'numeroRequisicao' => 123456,
    'agenciaDebito'    => 452,
    'contaCorrenteDebito' => 123456,
    'digitoVerificadorContaCorrenteDebito' => '8',
    'tipoPagamento' => 128, // PIX
    'listaTransferencias' => [
        [
            'numeroCOMPE'         => 1,
            'agenciaCredito'      => 100,
            'contaCorrenteCredito' => 654321,
            'digitoVerificadorContaCorrenteCredito' => '0',
            'valorTransferencia'  => 150.00,
            'descricaoTransferencia' => 'Pagamento fornecedor',
        ],
    ],
]);

// Consultar transferência
$transf = $bb->pagamentos()->consultarTransferencia('12345');

// Listar transferências
$lista = $bb->pagamentos()->listarTransferencias(['estado' => 'CONSISTENTE']);

// Pagar boleto por código de barras
$bb->pagamentos()->pagarBoleto([
    'numeroRequisicao' => 789,
    'agenciaDebito'    => 452,
    'contaCorrenteDebito' => 123456,
    'listaPagamentos' => [
        [
            'codigoBarras' => '23793381286000000001234567890180710000010000',
            'dataPagamento' => '01.02.2025',
            'valorPagamento' => 100.00,
        ],
    ],
]);

// Pagar guias (GPS, GRU, DARF)
$bb->pagamentos()->pagarGps([...]);
$bb->pagamentos()->pagarGru([...]);
$bb->pagamentos()->pagarDarf([...]);

// Consultar lote
$lote = $bb->pagamentos()->consultarLote('lote-001');

// Cancelar pagamento
$bb->pagamentos()->cancelarPagamento('12345');
```

### Laravel

```php
use CaiqueBispo\BancoDoBrasil\Laravel\Facades\BB;

BB::pagamentos()->transferir([...]);
BB::pagamentos()->pagarBoleto([...]);
```

## Extratos

```php
// Consultar saldo
$saldo = $bb->extratos()->saldo();

// Consultar extrato
$extrato = $bb->extratos()->extrato([
    'dataInicio' => '2025-01-01',
    'dataFim'    => '2025-01-31',
]);

// Extrato detalhado
$detalhado = $bb->extratos()->extratoDetalhado([
    'dataInicio' => '2025-01-01',
    'dataFim'    => '2025-01-31',
]);
```

### Laravel

```php
use CaiqueBispo\BancoDoBrasil\Laravel\Facades\BB;

$saldo   = BB::extratos()->saldo();
$extrato = BB::extratos()->extrato(['dataInicio' => '2025-01-01', 'dataFim' => '2025-01-31']);
```

## Webhook Handler

### PHP Puro

```php
use CaiqueBispo\BancoDoBrasil\Webhook\WebhookHandler;
use CaiqueBispo\BancoDoBrasil\Webhook\WebhookPayload;

$handler = new WebhookHandler('seu-webhook-secret');

$handler->on('pix', function (WebhookPayload $payload) {
    foreach ($payload->pixRecebidos() as $pix) {
        echo "PIX recebido: {$pix['endToEndId']} - R$ {$pix['valor']}\n";
    }
});

$handler->on('cobranca', function (WebhookPayload $payload) {
    $dados = $payload->toArray();
    echo "Boleto atualizado: {$dados['numeroBoletoBB']}\n";
});

$handler->on('*', function (WebhookPayload $payload) {
    // Captura todos os eventos
    file_put_contents('webhook.log', json_encode($payload->toArray()) . "\n", FILE_APPEND);
});

// Processar webhook recebido
$body      = file_get_contents('php://input');
$signature = $_SERVER['HTTP_X_WEBHOOK_SIGNATURE'] ?? null;

try {
    $payload = $handler->handle($body, $signature);
} catch (\CaiqueBispo\BancoDoBrasil\Exceptions\WebhookAuthorizationException $e) {
    http_response_code(401);
    echo $e->getMessage();
}
```

### Laravel

No `AppServiceProvider` ou em um Service Provider dedicado:

```php
use CaiqueBispo\BancoDoBrasil\Webhook\WebhookHandler;
use CaiqueBispo\BancoDoBrasil\Webhook\WebhookPayload;

public function boot(): void
{
    $handler = app(WebhookHandler::class);

    $handler->on('pix', function (WebhookPayload $payload) {
        foreach ($payload->pixRecebidos() as $pix) {
            \Log::info("PIX recebido: {$pix['endToEndId']}", $pix);
        }
    });

    $handler->on('cobranca', function (WebhookPayload $payload) {
        \Log::info('Boleto atualizado', $payload->toArray());
    });
}
```

Na rota:

```php
// routes/api.php
Route::post('/webhook/bb', function (Request $request) {
    $handler = app(WebhookHandler::class);

    $payload = $handler->handle(
        $request->getContent(),
        $request->header('X-Webhook-Signature'),
    );

    return response()->json(['ok' => true]);
});
```

## Tratamento de Erros

```php
use CaiqueBispo\BancoDoBrasil\Exceptions\BancoDoBrasilException;
use CaiqueBispo\BancoDoBrasil\Exceptions\AuthenticationException;
use CaiqueBispo\BancoDoBrasil\Exceptions\ContaNaoConfiguradaException;

try {
    $boleto = $bb->cobrancas()->registrar([...]);
} catch (AuthenticationException $e) {
    // Falha na autenticação OAuth
    echo "Erro OAuth: {$e->getMessage()}";
} catch (ContaNaoConfiguradaException $e) {
    // Conta não existe na configuração
    echo $e->getMessage();
} catch (BancoDoBrasilException $e) {
    echo "Erro: {$e->getMessage()}\n";
    echo "HTTP Status: {$e->statusCode}\n";
    echo "Código: {$e->codigoErro}\n";

    if ($e->isUnauthorized()) { /* 401 */ }
    if ($e->isForbidden()) { /* 403 */ }
    if ($e->isNotFound()) { /* 404 */ }
    if ($e->isValidationError()) { /* 400 */ }
    if ($e->isRateLimited()) { /* 429 */ }

    // Body completo da resposta
    print_r($e->responseBody);
}
```

## Certificado A1 (mTLS)

Para produção, é necessário configurar o certificado A1:

```php
'certificado' => [
    'path'  => '/caminho/para/certificado.pem',
    'senha' => 'senha-do-certificado',
],
```

O SDK automaticamente configura o mTLS no Guzzle quando o certificado é informado.

## Licença

MIT
