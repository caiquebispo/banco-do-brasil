<?php

namespace CaiqueBispo\BancoDoBrasil\Resources\Pix;

use CaiqueBispo\BancoDoBrasil\Resources\Resource;

class PixLoteCobVResource extends Resource
{
    protected function basePath(): string
    {
        return 'pix/v2/lotecobv';
    }

    public function criar(string $id, array $dados): array
    {
        return $this->client->put($this->path($id), $dados);
    }

    public function consultar(string $id): array
    {
        return $this->client->get($this->path($id));
    }

    public function revisar(string $id, array $dados): array
    {
        return $this->client->patch($this->path($id), $dados);
    }

    public function listar(array $query = []): array
    {
        return $this->client->get($this->basePath(), $query);
    }
}
