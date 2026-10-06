<?php

namespace CaiqueBispo\BancoDoBrasil\Tests\Unit;

use CaiqueBispo\BancoDoBrasil\Resources\Cobranca\CobrancaResource;
use CaiqueBispo\BancoDoBrasil\Tests\MockClient;
use PHPUnit\Framework\TestCase;

class CobrancaResourceTest extends TestCase
{
    private MockClient $client;

    private CobrancaResource $resource;

    protected function setUp(): void
    {
        $this->client = new MockClient();
        $this->client->respondWith(['id' => '00031285570000000001']);
        $this->resource = new CobrancaResource($this->client);
    }

    public function test_registrar(): void
    {
        $result = $this->resource->registrar(['valor' => 100.00]);
        $call = $this->client->lastCall();

        $this->assertEquals('POST', $call['method']);
        $this->assertEquals('cobrancas/v2/boletos', $call['path']);
        $this->assertEquals(['valor' => 100.00], $call['data']);
    }

    public function test_consultar(): void
    {
        $this->resource->consultar('00031285570000000001');
        $call = $this->client->lastCall();

        $this->assertEquals('GET', $call['method']);
        $this->assertEquals('cobrancas/v2/boletos/00031285570000000001', $call['path']);
    }

    public function test_listar(): void
    {
        $this->resource->listar(['agencia' => 452, 'conta' => 123456]);
        $call = $this->client->lastCall();

        $this->assertEquals('GET', $call['method']);
        $this->assertEquals('cobrancas/v2/boletos', $call['path']);
        $this->assertEquals(['agencia' => 452, 'conta' => 123456], $call['query']);
    }

    public function test_alterar(): void
    {
        $this->resource->alterar('00031285570000000001', ['dataVencimento' => '01.01.2025']);
        $call = $this->client->lastCall();

        $this->assertEquals('PATCH', $call['method']);
        $this->assertEquals('cobrancas/v2/boletos/00031285570000000001', $call['path']);
    }

    public function test_baixar(): void
    {
        $this->resource->baixar('00031285570000000001');
        $call = $this->client->lastCall();

        $this->assertEquals('POST', $call['method']);
        $this->assertEquals('cobrancas/v2/boletos/00031285570000000001/baixar', $call['path']);
    }

    public function test_listar_baixa_operacional(): void
    {
        $this->resource->listarBaixaOperacional(['agencia' => 452]);
        $call = $this->client->lastCall();

        $this->assertEquals('GET', $call['method']);
        $this->assertEquals('cobrancas/v2/boletos-baixa-operacional', $call['path']);
    }

    public function test_gerar_pix(): void
    {
        $this->resource->gerarPix('00031285570000000001');
        $call = $this->client->lastCall();

        $this->assertEquals('POST', $call['method']);
        $this->assertEquals('cobrancas/v2/boletos/00031285570000000001/gerar-pix', $call['path']);
    }

    public function test_consultar_pix(): void
    {
        $this->resource->consultarPix('00031285570000000001');
        $call = $this->client->lastCall();

        $this->assertEquals('GET', $call['method']);
        $this->assertEquals('cobrancas/v2/boletos/00031285570000000001/pix', $call['path']);
    }

    public function test_cancelar_pix(): void
    {
        $this->resource->cancelarPix('00031285570000000001');
        $call = $this->client->lastCall();

        $this->assertEquals('POST', $call['method']);
        $this->assertEquals('cobrancas/v2/boletos/00031285570000000001/cancelar-pix', $call['path']);
    }
}
