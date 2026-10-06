<?php

namespace CaiqueBispo\BancoDoBrasil\Tests\Unit;

use CaiqueBispo\BancoDoBrasil\BancoDoBrasil;
use CaiqueBispo\BancoDoBrasil\BancoDoBrasilConfig;
use CaiqueBispo\BancoDoBrasil\Resources\Cobranca\CobrancaResource;
use CaiqueBispo\BancoDoBrasil\Resources\Extrato\ExtratoResource;
use CaiqueBispo\BancoDoBrasil\Resources\Pagamento\PagamentoResource;
use CaiqueBispo\BancoDoBrasil\Resources\Pix\PixCobResource;
use CaiqueBispo\BancoDoBrasil\Resources\Pix\PixCobVResource;
use CaiqueBispo\BancoDoBrasil\Resources\Pix\PixLocationResource;
use CaiqueBispo\BancoDoBrasil\Resources\Pix\PixLoteCobVResource;
use CaiqueBispo\BancoDoBrasil\Resources\Pix\PixResource;
use CaiqueBispo\BancoDoBrasil\Resources\Pix\PixWebhookResource;
use PHPUnit\Framework\TestCase;

class BancoDoBrasilTest extends TestCase
{
    private function makeConfig(): BancoDoBrasilConfig
    {
        return new BancoDoBrasilConfig([
            'environment' => 'sandbox',
            'contas' => [
                'principal' => [
                    'client_id' => 'test-id',
                    'client_secret' => 'test-secret',
                    'developer_application_key' => 'test-key',
                    'convenio' => '3128557',
                ],
                'filial' => [
                    'client_id' => 'filial-id',
                    'client_secret' => 'filial-secret',
                    'developer_application_key' => 'filial-key',
                ],
            ],
        ]);
    }

    public function test_make_factory(): void
    {
        $bb = BancoDoBrasil::make([
            'environment' => 'sandbox',
            'contas' => [
                'principal' => [
                    'client_id' => 'id',
                    'client_secret' => 'secret',
                    'developer_application_key' => 'key',
                ],
            ],
        ]);

        $this->assertInstanceOf(BancoDoBrasil::class, $bb);
    }

    public function test_constructor(): void
    {
        $bb = new BancoDoBrasil($this->makeConfig());
        $this->assertInstanceOf(BancoDoBrasil::class, $bb);
    }

    public function test_conta_retorna_nova_instancia(): void
    {
        $bb = new BancoDoBrasil($this->makeConfig());
        $filial = $bb->conta('filial');

        $this->assertInstanceOf(BancoDoBrasil::class, $filial);
        $this->assertNotSame($bb, $filial);
        $this->assertEquals('filial', $filial->client()->context()->slug);
    }

    public function test_resource_cobrancas(): void
    {
        $bb = new BancoDoBrasil($this->makeConfig());
        $this->assertInstanceOf(CobrancaResource::class, $bb->cobrancas());
    }

    public function test_resource_pix_cob(): void
    {
        $bb = new BancoDoBrasil($this->makeConfig());
        $this->assertInstanceOf(PixCobResource::class, $bb->pixCob());
    }

    public function test_resource_pix_cobv(): void
    {
        $bb = new BancoDoBrasil($this->makeConfig());
        $this->assertInstanceOf(PixCobVResource::class, $bb->pixCobV());
    }

    public function test_resource_pix_lote_cobv(): void
    {
        $bb = new BancoDoBrasil($this->makeConfig());
        $this->assertInstanceOf(PixLoteCobVResource::class, $bb->pixLoteCobV());
    }

    public function test_resource_pix(): void
    {
        $bb = new BancoDoBrasil($this->makeConfig());
        $this->assertInstanceOf(PixResource::class, $bb->pix());
    }

    public function test_resource_pix_webhook(): void
    {
        $bb = new BancoDoBrasil($this->makeConfig());
        $this->assertInstanceOf(PixWebhookResource::class, $bb->pixWebhook());
    }

    public function test_resource_pix_location(): void
    {
        $bb = new BancoDoBrasil($this->makeConfig());
        $this->assertInstanceOf(PixLocationResource::class, $bb->pixLocation());
    }

    public function test_resource_pagamentos(): void
    {
        $bb = new BancoDoBrasil($this->makeConfig());
        $this->assertInstanceOf(PagamentoResource::class, $bb->pagamentos());
    }

    public function test_resource_extratos(): void
    {
        $bb = new BancoDoBrasil($this->makeConfig());
        $this->assertInstanceOf(ExtratoResource::class, $bb->extratos());
    }

    public function test_config_accessor(): void
    {
        $config = $this->makeConfig();
        $bb = new BancoDoBrasil($config);
        $this->assertSame($config, $bb->config());
    }
}
