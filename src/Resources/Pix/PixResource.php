<?php

namespace CaiqueBispo\BancoDoBrasil\Resources\Pix;

use CaiqueBispo\BancoDoBrasil\Resources\Resource;

class PixResource extends Resource
{
    protected function basePath(): string
    {
        return 'pix/v2/pix';
    }

    public function consultar(string $e2eId): array
    {
        return $this->client->get($this->path($e2eId));
    }

    public function listar(array $query = []): array
    {
        return $this->client->get($this->basePath(), $query);
    }

    public function devolver(string $e2eId, string $id, array $dados): array
    {
        return $this->client->put($this->path("{$e2eId}/devolucao/{$id}"), $dados);
    }

    public function consultarDevolucao(string $e2eId, string $id): array
    {
        return $this->client->get($this->path("{$e2eId}/devolucao/{$id}"));
    }
}
