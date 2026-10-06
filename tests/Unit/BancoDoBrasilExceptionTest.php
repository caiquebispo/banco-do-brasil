<?php

namespace CaiqueBispo\BancoDoBrasil\Tests\Unit;

use CaiqueBispo\BancoDoBrasil\Exceptions\BancoDoBrasilException;
use PHPUnit\Framework\TestCase;

class BancoDoBrasilExceptionTest extends TestCase
{
    public function test_from_response(): void
    {
        $exception = BancoDoBrasilException::fromResponse(400, [
            'mensagem' => 'Campo inválido',
            'codigoErro' => 'BB001',
            'erros' => [['campo' => 'valor', 'mensagem' => 'Valor inválido']],
        ]);

        $this->assertEquals('Campo inválido', $exception->getMessage());
        $this->assertEquals(400, $exception->statusCode);
        $this->assertEquals('BB001', $exception->codigoErro);
        $this->assertCount(1, $exception->erros);
    }

    public function test_from_response_fallback_message(): void
    {
        $exception = BancoDoBrasilException::fromResponse(500, []);
        $this->assertStringContains('HTTP 500', $exception->getMessage());
    }

    public function test_is_unauthorized(): void
    {
        $exception = new BancoDoBrasilException('Não autorizado', 401);
        $this->assertTrue($exception->isUnauthorized());
        $this->assertFalse($exception->isNotFound());
    }

    public function test_is_not_found(): void
    {
        $exception = new BancoDoBrasilException('Não encontrado', 404);
        $this->assertTrue($exception->isNotFound());
    }

    public function test_is_forbidden(): void
    {
        $exception = new BancoDoBrasilException('Proibido', 403);
        $this->assertTrue($exception->isForbidden());
    }

    public function test_is_validation_error(): void
    {
        $exception = new BancoDoBrasilException('Erro de validação', 400);
        $this->assertTrue($exception->isValidationError());
    }

    public function test_is_rate_limited(): void
    {
        $exception = new BancoDoBrasilException('Rate limited', 429);
        $this->assertTrue($exception->isRateLimited());
    }

    public function test_response_body(): void
    {
        $body = ['mensagem' => 'Erro', 'detalhes' => 'Info'];
        $exception = BancoDoBrasilException::fromResponse(422, $body);
        $this->assertEquals($body, $exception->responseBody);
    }

    private function assertStringContains(string $needle, string $haystack): void
    {
        $this->assertTrue(str_contains($haystack, $needle), "'{$haystack}' não contém '{$needle}'");
    }
}
