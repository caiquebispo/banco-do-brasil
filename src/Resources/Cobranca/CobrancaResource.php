<?php

namespace CaiqueBispo\BancoDoBrasil\Resources\Cobranca;

use CaiqueBispo\BancoDoBrasil\Resources\Resource;

class CobrancaResource extends Resource
{
    protected function basePath(): string
    {
        return 'cobrancas/v2/boletos';
    }

    public function registrar(array $dados): array
    {
        return $this->client->post($this->basePath(), $dados);
    }

    public function consultar(string $id): array
    {
        return $this->client->get($this->path($id));
    }

    public function listar(array $query = []): array
    {
        return $this->client->get($this->basePath(), $query);
    }

    public function alterar(string $id, array $dados): array
    {
        return $this->client->patch($this->path($id), $dados);
    }

    public function baixar(string $id, array $dados = []): array
    {
        return $this->client->post($this->path("{$id}/baixar"), $dados);
    }

    public function listarBaixaOperacional(array $query = []): array
    {
        return $this->client->get('cobrancas/v2/boletos-baixa-operacional', $query);
    }

    public function gerarPix(string $id, array $dados = []): array
    {
        return $this->client->post($this->path("{$id}/gerar-pix"), $dados);
    }

    public function consultarPix(string $id): array
    {
        return $this->client->get($this->path("{$id}/pix"));
    }

    public function cancelarPix(string $id, array $dados = []): array
    {
        return $this->client->post($this->path("{$id}/cancelar-pix"), $dados);
    }
}
