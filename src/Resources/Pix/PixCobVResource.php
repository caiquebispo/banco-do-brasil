<?php

namespace CaiqueBispo\BancoDoBrasil\Resources\Pix;

use CaiqueBispo\BancoDoBrasil\Resources\Resource;

class PixCobVResource extends Resource
{
    protected function basePath(): string
    {
        return 'pix/v2/cobv';
    }

    public function criar(string $txid, array $dados): array
    {
        return $this->client->put($this->path($txid), $dados);
    }

    public function consultar(string $txid): array
    {
        return $this->client->get($this->path($txid));
    }

    public function revisar(string $txid, array $dados): array
    {
        return $this->client->patch($this->path($txid), $dados);
    }

    public function listar(array $query = []): array
    {
        return $this->client->get($this->basePath(), $query);
    }
}
