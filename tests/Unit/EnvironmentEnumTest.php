<?php

namespace CaiqueBispo\BancoDoBrasil\Tests\Unit;

use CaiqueBispo\BancoDoBrasil\Enums\Environment;
use PHPUnit\Framework\TestCase;

class EnvironmentEnumTest extends TestCase
{
    public function test_sandbox_api_url(): void
    {
        $this->assertEquals('https://api.sandbox.bb.com.br', Environment::Sandbox->apiBaseUrl());
    }

    public function test_homologacao_api_url(): void
    {
        $this->assertEquals('https://api.hm.bb.com.br', Environment::Homologacao->apiBaseUrl());
    }

    public function test_producao_api_url(): void
    {
        $this->assertEquals('https://api.bb.com.br', Environment::Producao->apiBaseUrl());
    }

    public function test_sandbox_oauth_url(): void
    {
        $this->assertEquals('https://oauth.sandbox.bb.com.br', Environment::Sandbox->oauthBaseUrl());
    }

    public function test_homologacao_oauth_url(): void
    {
        $this->assertEquals('https://oauth.hm.bb.com.br', Environment::Homologacao->oauthBaseUrl());
    }

    public function test_producao_oauth_url(): void
    {
        $this->assertEquals('https://oauth.bb.com.br', Environment::Producao->oauthBaseUrl());
    }

    public function test_from_string(): void
    {
        $this->assertEquals(Environment::Sandbox, Environment::from('sandbox'));
        $this->assertEquals(Environment::Homologacao, Environment::from('homologacao'));
        $this->assertEquals(Environment::Producao, Environment::from('producao'));
    }
}
