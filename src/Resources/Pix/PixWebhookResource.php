<?php

namespace CaiqueBispo\BancoDoBrasil\Resources\Pix;

use CaiqueBispo\BancoDoBrasil\Resources\Resource;

class PixWebhookResource extends Resource
{
    protected function basePath(): string
    {
        return 'pix/v2/webhook';
    }

    public function criar(string $chave, array $dados): array
    {
        return $this->client->put($this->path($chave), $dados);
    }

    public function consultar(string $chave): array
    {
        return $this->client->get($this->path($chave));
    }

    public function excluir(string $chave): array
    {
        return $this->client->delete($this->path($chave));
    }

    public function listar(array $query = []): array
    {
        return $this->client->get($this->basePath(), $query);
    }
}
