<?php

namespace CaiqueBispo\BancoDoBrasil\Tests\Unit;

use CaiqueBispo\BancoDoBrasil\BancoDoBrasilConfig;
use CaiqueBispo\BancoDoBrasil\Enums\Environment;
use CaiqueBispo\BancoDoBrasil\Http\AccountContext;
use PHPUnit\Framework\TestCase;

class AccountContextTest extends TestCase
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
                    'carteira' => 17,
                    'variacao_carteira' => 35,
                    'chave_pix' => 'chave@test.com',
                ],
                'filial' => [
                    'client_id' => 'filial-id',
                    'client_secret' => 'filial-secret',
                    'developer_application_key' => 'filial-key',
                ],
            ],
        ]);
    }

    public function test_from_config(): void
    {
        $context = AccountContext::fromConfig($this->makeConfig(), 'principal');

        $this->assertEquals('principal', $context->slug);
        $this->assertEquals('test-id', $context->clientId);
        $this->assertEquals('test-secret', $context->clientSecret);
        $this->assertEquals('test-key', $context->developerApplicationKey);
        $this->assertEquals('3128557', $context->convenio);
        $this->assertEquals(17, $context->carteira);
        $this->assertEquals(35, $context->variacaoCarteira);
        $this->assertEquals('chave@test.com', $context->chavePix);
        $this->assertEquals(Environment::Sandbox, $context->environment);
    }

    public function test_with_environment(): void
    {
        $context = AccountContext::fromConfig($this->makeConfig(), 'principal');
        $prod = $context->withEnvironment(Environment::Producao);

        $this->assertEquals(Environment::Producao, $prod->environment);
        $this->assertEquals('principal', $prod->slug);
        $this->assertNotSame($context, $prod);
    }

    public function test_with_conta(): void
    {
        $context = AccountContext::fromConfig($this->makeConfig(), 'principal');
        $filial = $context->withConta('filial');

        $this->assertEquals('filial', $filial->slug);
        $this->assertEquals('filial-id', $filial->clientId);
        $this->assertNotSame($context, $filial);
    }

    public function test_nullable_fields(): void
    {
        $context = AccountContext::fromConfig($this->makeConfig(), 'filial');

        $this->assertNull($context->convenio);
        $this->assertNull($context->carteira);
        $this->assertNull($context->variacaoCarteira);
        $this->assertNull($context->chavePix);
    }
}
