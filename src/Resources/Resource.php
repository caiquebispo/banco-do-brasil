<?php

namespace CaiqueBispo\BancoDoBrasil\Resources;

use CaiqueBispo\BancoDoBrasil\Contracts\BancoDoBrasilClientInterface;

abstract class Resource
{
    public function __construct(
        protected readonly BancoDoBrasilClientInterface $client,
    ) {}

    abstract protected function basePath(): string;

    protected function path(string $suffix = ''): string
    {
        $base = $this->basePath();

        return $suffix ? "{$base}/{$suffix}" : $base;
    }

    protected function convenio(): ?string
    {
        return $this->client->context()->convenio;
    }

    protected function contaSlug(): string
    {
        return $this->client->context()->slug;
    }
}
