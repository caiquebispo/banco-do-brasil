<?php

namespace CaiqueBispo\BancoDoBrasil\Tests\Unit;

use CaiqueBispo\BancoDoBrasil\BancoDoBrasilConfig;
use CaiqueBispo\BancoDoBrasil\Enums\Environment;
use CaiqueBispo\BancoDoBrasil\Exceptions\ContaNaoConfiguradaException;
use PHPUnit\Framework\TestCase;

class BancoDoBrasilConfigTest extends TestCase
{
    private function makeConfig(array $overrides = []): BancoDoBrasilConfig
    {
        return new BancoDoBrasilConfig(array_merge([
            'environment' => 'sandbox',
            'contas' => [
                'principal' => [
                    'client_id' => 'test-client-id',
                    'client_secret' => 'test-client-secret',
                    'developer_application_key' => 'test-dev-key',
                    'convenio' => '3128557',
                    'carteira' => 17,
                    'variacao_carteira' => 35,
                    'chave_pix' => 'chave@test.com',
                ],
                'filial' => [
                    'client_id' => 'filial-client-id',
                    'client_secret' => 'filial-secret',
                    'developer_application_key' => 'filial-dev-key',
                    'convenio' => '9876543',
                ],
            ],
            'default' => 'principal',
            'timeout' => 30,
            'retry' => ['times' => 3, 'sleep' => 500],
            'certificado' => ['path' => '/certs/cert.pem', 'senha' => 'senha123'],
            'webhook' => ['secret' => 'webhook-secret'],
        ], $overrides));
    }

    public function test_environment(): void
    {
        $config = $this->makeConfig();
        $this->assertEquals(Environment::Sandbox, $config->environment());
    }

    public function test_producao_environment(): void
    {
        $config = $this->makeConfig(['environment' => 'producao']);
        $this->assertEquals(Environment::Producao, $config->environment());
    }

    public function test_default_conta(): void
    {
        $config = $this->makeConfig();
        $this->assertEquals('principal', $config->defaultConta());
    }

    public function test_client_id(): void
    {
        $config = $this->makeConfig();
        $this->assertEquals('test-client-id', $config->clientId('principal'));
    }

    public function test_client_secret(): void
    {
        $config = $this->makeConfig();
        $this->assertEquals('test-client-secret', $config->clientSecret('principal'));
    }

    public function test_developer_application_key(): void
    {
        $config = $this->makeConfig();
        $this->assertEquals('test-dev-key', $config->developerApplicationKey('principal'));
    }

    public function test_convenio(): void
    {
        $config = $this->makeConfig();
        $this->assertEquals('3128557', $config->convenio('principal'));
    }

    public function test_carteira(): void
    {
        $config = $this->makeConfig();
        $this->assertEquals(17, $config->carteira('principal'));
    }

    public function test_variacao_carteira(): void
    {
        $config = $this->makeConfig();
        $this->assertEquals(35, $config->variacaoCarteira('principal'));
    }

    public function test_chave_pix(): void
    {
        $config = $this->makeConfig();
        $this->assertEquals('chave@test.com', $config->chavePix('principal'));
    }

    public function test_filial_conta(): void
    {
        $config = $this->makeConfig();
        $this->assertEquals('filial-client-id', $config->clientId('filial'));
        $this->assertEquals('9876543', $config->convenio('filial'));
    }

    public function test_timeout(): void
    {
        $config = $this->makeConfig();
        $this->assertEquals(30, $config->timeout());
    }

    public function test_retry(): void
    {
        $config = $this->makeConfig();
        $this->assertEquals(3, $config->retryTimes());
        $this->assertEquals(500, $config->retrySleep());
    }

    public function test_certificado(): void
    {
        $config = $this->makeConfig();
        $this->assertEquals('/certs/cert.pem', $config->certificadoPath());
        $this->assertEquals('senha123', $config->certificadoSenha());
    }

    public function test_webhook_secret(): void
    {
        $config = $this->makeConfig();
        $this->assertEquals('webhook-secret', $config->webhookSecret());
    }

    public function test_conta_nao_configurada(): void
    {
        $config = $this->makeConfig();
        $this->expectException(ContaNaoConfiguradaException::class);
        $config->conta('inexistente');
    }

    public function test_nullable_fields(): void
    {
        $config = $this->makeConfig([
            'certificado' => [],
            'webhook' => [],
        ]);
        $this->assertNull($config->certificadoPath());
        $this->assertNull($config->certificadoSenha());
        $this->assertNull($config->webhookSecret());
    }

    public function test_carteira_null_quando_nao_configurada(): void
    {
        $config = $this->makeConfig();
        $this->assertNull($config->carteira('filial'));
        $this->assertNull($config->variacaoCarteira('filial'));
    }
}
