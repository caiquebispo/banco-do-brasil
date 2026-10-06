<?php

namespace CaiqueBispo\BancoDoBrasil\Tests\Unit;

use CaiqueBispo\BancoDoBrasil\Exceptions\WebhookAuthorizationException;
use CaiqueBispo\BancoDoBrasil\Webhook\WebhookHandler;
use CaiqueBispo\BancoDoBrasil\Webhook\WebhookPayload;
use PHPUnit\Framework\TestCase;

class WebhookHandlerTest extends TestCase
{
    public function test_handle_pix_payload(): void
    {
        $handler = new WebhookHandler();
        $received = null;

        $handler->on('pix', function (WebhookPayload $payload) use (&$received) {
            $received = $payload;
        });

        $body = json_encode(['pix' => [['endToEndId' => 'E123', 'valor' => '10.00']]]);
        $handler->handle($body);

        $this->assertNotNull($received);
        $this->assertTrue($received->isPix());
        $this->assertEquals('pix', $received->tipo());
    }

    public function test_handle_cobranca_payload(): void
    {
        $handler = new WebhookHandler();
        $received = null;

        $handler->on('cobranca', function (WebhookPayload $payload) use (&$received) {
            $received = $payload;
        });

        $body = json_encode(['numeroBoletoBB' => '00031285570000000001', 'codigoLinhaDigitavel' => '123']);
        $handler->handle($body);

        $this->assertNotNull($received);
        $this->assertTrue($received->isCobranca());
        $this->assertEquals('cobranca', $received->tipo());
    }

    public function test_wildcard_listener(): void
    {
        $handler = new WebhookHandler();
        $received = null;

        $handler->on('*', function (WebhookPayload $payload) use (&$received) {
            $received = $payload;
        });

        $body = json_encode(['pix' => [['valor' => '5.00']]]);
        $handler->handle($body);

        $this->assertNotNull($received);
    }

    public function test_signature_verification_success(): void
    {
        $secret = 'meu-secret';
        $handler = new WebhookHandler($secret);
        $received = null;

        $handler->on('pix', function (WebhookPayload $payload) use (&$received) {
            $received = $payload;
        });

        $body = json_encode(['pix' => [['valor' => '100.00']]]);
        $signature = hash_hmac('sha256', $body, $secret);

        $handler->handle($body, $signature);

        $this->assertNotNull($received);
    }

    public function test_signature_verification_failure(): void
    {
        $handler = new WebhookHandler('secret');

        $this->expectException(WebhookAuthorizationException::class);
        $this->expectExceptionMessage('Assinatura inválida');

        $handler->handle('{"pix":[]}', 'assinatura-errada');
    }

    public function test_missing_signature(): void
    {
        $handler = new WebhookHandler('secret');

        $this->expectException(WebhookAuthorizationException::class);
        $this->expectExceptionMessage('Assinatura ausente');

        $handler->handle('{"pix":[]}');
    }

    public function test_without_secret_skips_verification(): void
    {
        $handler = new WebhookHandler();
        $payload = $handler->handle('{"pix":[{"valor":"1.00"}]}');

        $this->assertTrue($payload->isPix());
    }

    public function test_unknown_type(): void
    {
        $handler = new WebhookHandler();
        $payload = $handler->handle('{"outroCampo": "valor"}');

        $this->assertEquals('desconhecido', $payload->tipo());
    }

    public function test_multiple_listeners_same_event(): void
    {
        $handler = new WebhookHandler();
        $count = 0;

        $handler->on('pix', function () use (&$count) { $count++; });
        $handler->on('pix', function () use (&$count) { $count++; });

        $handler->handle('{"pix":[]}');

        $this->assertEquals(2, $count);
    }
}
