<?php

namespace CaiqueBispo\BancoDoBrasil\Resources\Extrato;

use CaiqueBispo\BancoDoBrasil\Resources\Resource;

class ExtratoResource extends Resource
{
    protected function basePath(): string
    {
        return 'extratos/v1';
    }

    public function saldo(array $query = []): array
    {
        return $this->client->get($this->path('saldo'), $query);
    }

    public function extrato(array $query = []): array
    {
        return $this->client->get($this->path('extrato'), $query);
    }

    public function extratoDetalhado(array $query = []): array
    {
        return $this->client->get($this->path('extrato-detalhado'), $query);
    }
}
