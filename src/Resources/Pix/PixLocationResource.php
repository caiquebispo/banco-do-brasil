<?php

namespace CaiqueBispo\BancoDoBrasil\Resources\Pix;

use CaiqueBispo\BancoDoBrasil\Resources\Resource;

class PixLocationResource extends Resource
{
    protected function basePath(): string
    {
        return 'pix/v2/loc';
    }

    public function criar(array $dados): array
    {
        return $this->client->post($this->basePath(), $dados);
    }

    public function consultar(int $id): array
    {
        return $this->client->get($this->path((string) $id));
    }

    public function listar(array $query = []): array
    {
        return $this->client->get($this->basePath(), $query);
    }

    public function desvincular(int $id): array
    {
        return $this->client->delete($this->path("{$id}/txid"));
    }

    public function qrcode(int $id): array
    {
        return $this->client->get($this->path("{$id}/qrcode"));
    }
}
