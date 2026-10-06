<?php

namespace CaiqueBispo\BancoDoBrasil;

use CaiqueBispo\BancoDoBrasil\Enums\Environment;
use CaiqueBispo\BancoDoBrasil\Exceptions\ContaNaoConfiguradaException;

class BancoDoBrasilConfig
{
    private Environment $environment;

    /** @var array<string, array{client_id: string, client_secret: string, developer_application_key: string, convenio?: string, carteira?: int, variacao_carteira?: int, chave_pix?: string}> */
    private array $contas;

    private string $default;

    private int $timeout;

    private int $retryTimes;

    private int $retrySleep;

    private ?string $certificadoPath;

    private ?string $certificadoSenha;

    private ?string $webhookSecret;

    public function __construct(array $config)
    {
        $this->environment = Environment::from($config['environment'] ?? 'sandbox');
        $this->contas = $config['contas'] ?? [];
        $this->default = $config['default'] ?? array_key_first($this->contas) ?? 'principal';
        $this->timeout = $config['timeout'] ?? 30;
        $this->retryTimes = $config['retry']['times'] ?? 3;
        $this->retrySleep = $config['retry']['sleep'] ?? 500;
        $this->certificadoPath = $config['certificado']['path'] ?? null;
        $this->certificadoSenha = $config['certificado']['senha'] ?? null;
        $this->webhookSecret = $config['webhook']['secret'] ?? null;
    }

    public function environment(): Environment
    {
        return $this->environment;
    }

    public function defaultConta(): string
    {
        return $this->default;
    }

    public function conta(string $slug): array
    {
        if (! isset($this->contas[$slug])) {
            throw ContaNaoConfiguradaException::make($slug);
        }

        return $this->contas[$slug];
    }

    public function clientId(string $slug): string
    {
        return $this->conta($slug)['client_id'];
    }

    public function clientSecret(string $slug): string
    {
        return $this->conta($slug)['client_secret'];
    }

    public function developerApplicationKey(string $slug): string
    {
        return $this->conta($slug)['developer_application_key'];
    }

    public function convenio(string $slug): ?string
    {
        return $this->conta($slug)['convenio'] ?? null;
    }

    public function carteira(string $slug): ?int
    {
        return $this->conta($slug)['carteira'] ?? null;
    }

    public function variacaoCarteira(string $slug): ?int
    {
        return $this->conta($slug)['variacao_carteira'] ?? null;
    }

    public function chavePix(string $slug): ?string
    {
        return $this->conta($slug)['chave_pix'] ?? null;
    }

    public function timeout(): int
    {
        return $this->timeout;
    }

    public function retryTimes(): int
    {
        return $this->retryTimes;
    }

    public function retrySleep(): int
    {
        return $this->retrySleep;
    }

    public function certificadoPath(): ?string
    {
        return $this->certificadoPath;
    }

    public function certificadoSenha(): ?string
    {
        return $this->certificadoSenha;
    }

    public function webhookSecret(): ?string
    {
        return $this->webhookSecret;
    }
}
