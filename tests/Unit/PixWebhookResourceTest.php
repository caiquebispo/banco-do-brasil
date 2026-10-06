<?php

namespace CaiqueBispo\BancoDoBrasil\Tests\Unit;

use CaiqueBispo\BancoDoBrasil\Resources\Pix\PixWebhookResource;
use CaiqueBispo\BancoDoBrasil\Tests\MockClient;
use PHPUnit\Framework\TestCase;

class PixWebhookResourceTest extends TestCase
{
    private MockClient $client;

    private PixWebhookResource $resource;

    protected function setUp(): void
    {
        $this->client = new MockClient();
        $this->client->respondWith(['webhookUrl' => 'https://example.com/webhook']);
        $this->resource = new PixWebhookResource($this->client);
    }

    public function test_criar(): void
    {
        $this->resource->criar('chave@pix.com', ['webhookUrl' => 'https://example.com/webhook']);
        $call = $this->client->lastCall();

        $this->assertEquals('PUT', $call['method']);
        $this->assertEquals('pix/v2/webhook/chave@pix.com', $call['path']);
    }

    public function test_consultar(): void
    {
        $this->resource->consultar('chave@pix.com');
        $call = $this->client->lastCall();

        $this->assertEquals('GET', $call['method']);
        $this->assertEquals('pix/v2/webhook/chave@pix.com', $call['path']);
    }

    public function test_excluir(): void
    {
        $this->resource->excluir('chave@pix.com');
        $call = $this->client->lastCall();

        $this->assertEquals('DELETE', $call['method']);
        $this->assertEquals('pix/v2/webhook/chave@pix.com', $call['path']);
    }

    public function test_listar(): void
    {
        $this->resource->listar(['inicio' => '2024-01-01T00:00:00Z']);
        $call = $this->client->lastCall();

        $this->assertEquals('GET', $call['method']);
        $this->assertEquals('pix/v2/webhook', $call['path']);
    }
}
