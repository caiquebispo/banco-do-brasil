<?php

namespace CaiqueBispo\BancoDoBrasil\Tests\Unit;

use CaiqueBispo\BancoDoBrasil\Resources\Pagamento\PagamentoResource;
use CaiqueBispo\BancoDoBrasil\Tests\MockClient;
use PHPUnit\Framework\TestCase;

class PagamentoResourceTest extends TestCase
{
    private MockClient $client;

    private PagamentoResource $resource;

    protected function setUp(): void
    {
        $this->client = new MockClient();
        $this->client->respondWith(['id' => '12345']);
        $this->resource = new PagamentoResource($this->client);
    }

    public function test_transferir(): void
    {
        $this->resource->transferir(['valor' => 1000]);
        $call = $this->client->lastCall();

        $this->assertEquals('POST', $call['method']);
        $this->assertEquals('pagamentos-lote/v1/transferencias', $call['path']);
    }

    public function test_consultar_transferencia(): void
    {
        $this->resource->consultarTransferencia('12345');
        $call = $this->client->lastCall();

        $this->assertEquals('GET', $call['method']);
        $this->assertEquals('pagamentos-lote/v1/transferencias/12345', $call['path']);
    }

    public function test_listar_transferencias(): void
    {
        $this->resource->listarTransferencias(['estado' => 'CONSISTENTE']);
        $call = $this->client->lastCall();

        $this->assertEquals('GET', $call['method']);
        $this->assertEquals('pagamentos-lote/v1/transferencias', $call['path']);
    }

    public function test_pagar_boleto(): void
    {
        $this->resource->pagarBoleto(['codigoBarras' => '23793.38128 60000.000003 12345.678909 1 83710000010000']);
        $call = $this->client->lastCall();

        $this->assertEquals('POST', $call['method']);
        $this->assertEquals('pagamentos-lote/v1/boletos', $call['path']);
    }

    public function test_consultar_pagamento_boleto(): void
    {
        $this->resource->consultarPagamentoBoleto('12345');
        $call = $this->client->lastCall();

        $this->assertEquals('GET', $call['method']);
        $this->assertEquals('pagamentos-lote/v1/boletos/12345', $call['path']);
    }

    public function test_listar_pagamentos_boletos(): void
    {
        $this->resource->listarPagamentosBoletos();
        $call = $this->client->lastCall();

        $this->assertEquals('GET', $call['method']);
        $this->assertEquals('pagamentos-lote/v1/boletos', $call['path']);
    }

    public function test_pagar_guia(): void
    {
        $this->resource->pagarGuia(['codigoBarras' => '858000000']);
        $call = $this->client->lastCall();

        $this->assertEquals('POST', $call['method']);
        $this->assertEquals('pagamentos-lote/v1/guias-codigo-barras', $call['path']);
    }

    public function test_consultar_pagamento_guia(): void
    {
        $this->resource->consultarPagamentoGuia('12345');
        $call = $this->client->lastCall();

        $this->assertEquals('GET', $call['method']);
        $this->assertEquals('pagamentos-lote/v1/guias-codigo-barras/12345', $call['path']);
    }

    public function test_listar_pagamentos_guias(): void
    {
        $this->resource->listarPagamentosGuias();
        $call = $this->client->lastCall();

        $this->assertEquals('GET', $call['method']);
        $this->assertEquals('pagamentos-lote/v1/guias-codigo-barras', $call['path']);
    }

    public function test_pagar_gru(): void
    {
        $this->resource->pagarGru(['codigoRecolhimento' => '123']);
        $call = $this->client->lastCall();

        $this->assertEquals('POST', $call['method']);
        $this->assertEquals('pagamentos-lote/v1/gru', $call['path']);
    }

    public function test_pagar_darf(): void
    {
        $this->resource->pagarDarf(['codigoReceita' => '0190']);
        $call = $this->client->lastCall();

        $this->assertEquals('POST', $call['method']);
        $this->assertEquals('pagamentos-lote/v1/darf', $call['path']);
    }

    public function test_pagar_gps(): void
    {
        $this->resource->pagarGps(['codigoPagamento' => '2100']);
        $call = $this->client->lastCall();

        $this->assertEquals('POST', $call['method']);
        $this->assertEquals('pagamentos-lote/v1/gps', $call['path']);
    }

    public function test_consultar_lote(): void
    {
        $this->resource->consultarLote('lote-001');
        $call = $this->client->lastCall();

        $this->assertEquals('GET', $call['method']);
        $this->assertEquals('pagamentos-lote/v1/lotes/lote-001', $call['path']);
    }

    public function test_cancelar_pagamento(): void
    {
        $this->resource->cancelarPagamento('12345');
        $call = $this->client->lastCall();

        $this->assertEquals('POST', $call['method']);
        $this->assertEquals('pagamentos-lote/v1/pagamentos/12345/cancelar', $call['path']);
    }
}
