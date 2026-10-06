<?php

namespace CaiqueBispo\BancoDoBrasil;

use CaiqueBispo\BancoDoBrasil\Auth\OAuthTokenManager;
use CaiqueBispo\BancoDoBrasil\Contracts\BancoDoBrasilClientInterface;
use CaiqueBispo\BancoDoBrasil\Enums\Environment;
use CaiqueBispo\BancoDoBrasil\Http\AccountContext;
use CaiqueBispo\BancoDoBrasil\Http\BancoDoBrasilClient;
use CaiqueBispo\BancoDoBrasil\Resources\Cobranca\CobrancaResource;
use CaiqueBispo\BancoDoBrasil\Resources\Extrato\ExtratoResource;
use CaiqueBispo\BancoDoBrasil\Resources\Pagamento\PagamentoResource;
use CaiqueBispo\BancoDoBrasil\Resources\Pix\PixCobResource;
use CaiqueBispo\BancoDoBrasil\Resources\Pix\PixCobVResource;
use CaiqueBispo\BancoDoBrasil\Resources\Pix\PixLocationResource;
use CaiqueBispo\BancoDoBrasil\Resources\Pix\PixLoteCobVResource;
use CaiqueBispo\BancoDoBrasil\Resources\Pix\PixResource;
use CaiqueBispo\BancoDoBrasil\Resources\Pix\PixWebhookResource;

class BancoDoBrasil
{
    private BancoDoBrasilConfig $config;

    private BancoDoBrasilClientInterface $client;

    private OAuthTokenManager $tokenManager;

    public function __construct(BancoDoBrasilConfig $config, ?OAuthTokenManager $tokenManager = null)
    {
        $this->config = $config;
        $this->tokenManager = $tokenManager ?? new OAuthTokenManager();

        $context = AccountContext::fromConfig($config, $config->defaultConta());
        $this->client = new BancoDoBrasilClient($context, $this->tokenManager);
    }

    public static function make(array $config): static
    {
        return new static(new BancoDoBrasilConfig($config));
    }

    public function conta(string $slug): static
    {
        $context = AccountContext::fromConfig($this->config, $slug);
        $clone = clone $this;
        $clone->client = new BancoDoBrasilClient($context, $this->tokenManager);

        return $clone;
    }

    public function environment(Environment $environment): static
    {
        $context = $this->client->context()->withEnvironment($environment);
        $clone = clone $this;
        $clone->client = new BancoDoBrasilClient($context, $this->tokenManager);

        return $clone;
    }

    public function cobrancas(): CobrancaResource
    {
        return new CobrancaResource($this->client);
    }

    public function pixCob(): PixCobResource
    {
        return new PixCobResource($this->client);
    }

    public function pixCobV(): PixCobVResource
    {
        return new PixCobVResource($this->client);
    }

    public function pixLoteCobV(): PixLoteCobVResource
    {
        return new PixLoteCobVResource($this->client);
    }

    public function pix(): PixResource
    {
        return new PixResource($this->client);
    }

    public function pixWebhook(): PixWebhookResource
    {
        return new PixWebhookResource($this->client);
    }

    public function pixLocation(): PixLocationResource
    {
        return new PixLocationResource($this->client);
    }

    public function pagamentos(): PagamentoResource
    {
        return new PagamentoResource($this->client);
    }

    public function extratos(): ExtratoResource
    {
        return new ExtratoResource($this->client);
    }

    public function client(): BancoDoBrasilClientInterface
    {
        return $this->client;
    }

    public function config(): BancoDoBrasilConfig
    {
        return $this->config;
    }
}
