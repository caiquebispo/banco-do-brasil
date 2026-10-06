<?php

namespace CaiqueBispo\BancoDoBrasil\Http;

use CaiqueBispo\BancoDoBrasil\BancoDoBrasilConfig;
use CaiqueBispo\BancoDoBrasil\Enums\Environment;

class AccountContext
{
    public function __construct(
        public readonly string $slug,
        public readonly string $clientId,
        public readonly string $clientSecret,
        public readonly string $developerApplicationKey,
        public readonly ?string $convenio,
        public readonly ?int $carteira,
        public readonly ?int $variacaoCarteira,
        public readonly ?string $chavePix,
        public readonly Environment $environment,
        public readonly BancoDoBrasilConfig $config,
    ) {}

    public static function fromConfig(BancoDoBrasilConfig $config, string $slug, ?Environment $environment = null): static
    {
        $env = $environment ?? $config->environment();

        return new static(
            slug: $slug,
            clientId: $config->clientId($slug),
            clientSecret: $config->clientSecret($slug),
            developerApplicationKey: $config->developerApplicationKey($slug),
            convenio: $config->convenio($slug),
            carteira: $config->carteira($slug),
            variacaoCarteira: $config->variacaoCarteira($slug),
            chavePix: $config->chavePix($slug),
            environment: $env,
            config: $config,
        );
    }

    public function withEnvironment(Environment $environment): static
    {
        return static::fromConfig($this->config, $this->slug, $environment);
    }

    public function withConta(string $slug): static
    {
        return static::fromConfig($this->config, $slug, $this->environment);
    }
}
