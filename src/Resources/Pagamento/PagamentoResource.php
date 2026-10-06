<?php

namespace CaiqueBispo\BancoDoBrasil\Resources\Pagamento;

use CaiqueBispo\BancoDoBrasil\Resources\Resource;

class PagamentoResource extends Resource
{
    protected function basePath(): string
    {
        return 'pagamentos-lote/v1';
    }

    public function transferir(array $dados): array
    {
        return $this->client->post($this->path('transferencias'), $dados);
    }

    public function consultarTransferencia(string $id): array
    {
        return $this->client->get($this->path("transferencias/{$id}"));
    }

    public function listarTransferencias(array $query = []): array
    {
        return $this->client->get($this->path('transferencias'), $query);
    }

    public function pagarBoleto(array $dados): array
    {
        return $this->client->post($this->path('boletos'), $dados);
    }

    public function consultarPagamentoBoleto(string $id): array
    {
        return $this->client->get($this->path("boletos/{$id}"));
    }

    public function listarPagamentosBoletos(array $query = []): array
    {
        return $this->client->get($this->path('boletos'), $query);
    }

    public function pagarGuia(array $dados): array
    {
        return $this->client->post($this->path('guias-codigo-barras'), $dados);
    }

    public function consultarPagamentoGuia(string $id): array
    {
        return $this->client->get($this->path("guias-codigo-barras/{$id}"));
    }

    public function listarPagamentosGuias(array $query = []): array
    {
        return $this->client->get($this->path('guias-codigo-barras'), $query);
    }

    public function pagarGru(array $dados): array
    {
        return $this->client->post($this->path('gru'), $dados);
    }

    public function pagarDarf(array $dados): array
    {
        return $this->client->post($this->path('darf'), $dados);
    }

    public function pagarGps(array $dados): array
    {
        return $this->client->post($this->path('gps'), $dados);
    }

    public function consultarLote(string $id): array
    {
        return $this->client->get($this->path("lotes/{$id}"));
    }

    public function cancelarPagamento(string $id, array $dados = []): array
    {
        return $this->client->post($this->path("pagamentos/{$id}/cancelar"), $dados);
    }
}
