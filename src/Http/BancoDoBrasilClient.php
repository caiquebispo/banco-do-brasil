<?php

namespace CaiqueBispo\BancoDoBrasil\Http;

use CaiqueBispo\BancoDoBrasil\Auth\OAuthTokenManager;
use CaiqueBispo\BancoDoBrasil\Contracts\BancoDoBrasilClientInterface;
use CaiqueBispo\BancoDoBrasil\Exceptions\BancoDoBrasilException;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Exception\RequestException;

class BancoDoBrasilClient implements BancoDoBrasilClientInterface
{
    private Client $http;

    private OAuthTokenManager $tokenManager;

    public function __construct(
        private readonly AccountContext $accountContext,
        ?OAuthTokenManager $tokenManager = null,
    ) {
        $this->tokenManager = $tokenManager ?? new OAuthTokenManager();

        $options = [
            'base_uri' => $this->accountContext->environment->apiBaseUrl() . '/',
            'headers' => [
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
            ],
            'timeout' => $this->accountContext->config->timeout(),
        ];

        $certPath = $this->accountContext->config->certificadoPath();
        if ($certPath) {
            $senha = $this->accountContext->config->certificadoSenha();
            $options['cert'] = $senha ? [$certPath, $senha] : $certPath;
        }

        $this->http = new Client($options);
    }

    public function get(string $path, array $query = []): array
    {
        return $this->request('GET', $path, query: $query);
    }

    public function post(string $path, array $data = [], array $query = []): array
    {
        return $this->request('POST', $path, $data, $query);
    }

    public function put(string $path, array $data = [], array $query = []): array
    {
        return $this->request('PUT', $path, $data, $query);
    }

    public function patch(string $path, array $data = [], array $query = []): array
    {
        return $this->request('PATCH', $path, $data, $query);
    }

    public function delete(string $path, array $data = [], array $query = []): array
    {
        return $this->request('DELETE', $path, $data, $query);
    }

    public function context(): AccountContext
    {
        return $this->accountContext;
    }

    private function request(string $method, string $path, array $data = [], array $query = []): array
    {
        $token = $this->tokenManager->getToken(
            $this->accountContext->clientId,
            $this->accountContext->clientSecret,
            $this->accountContext->environment,
        );

        $query['gw-dev-app-key'] = $this->accountContext->developerApplicationKey;

        $options = [
            'headers' => [
                'Authorization' => "Bearer {$token}",
            ],
            'query' => $query,
        ];

        if (! empty($data)) {
            $options['json'] = $data;
        }

        $attempts = $this->accountContext->config->retryTimes();
        $sleep = $this->accountContext->config->retrySleep();
        $lastException = null;

        for ($i = 0; $i < $attempts; $i++) {
            try {
                $response = $this->http->request($method, $path, $options);
                $contents = $response->getBody()->getContents();

                return $contents ? (json_decode($contents, true) ?? []) : [];
            } catch (ConnectException $e) {
                $lastException = $e;
                if ($i < $attempts - 1) {
                    usleep($sleep * 1000);
                }
            } catch (RequestException $e) {
                if (! $e->hasResponse()) {
                    throw new BancoDoBrasilException($e->getMessage(), 0);
                }

                $status = $e->getResponse()->getStatusCode();
                $body = json_decode($e->getResponse()->getBody()->getContents(), true) ?? [];

                throw BancoDoBrasilException::fromResponse($status, $body);
            }
        }

        throw new BancoDoBrasilException(
            "Falha de conexão após {$attempts} tentativas: " . $lastException?->getMessage(),
            0,
        );
    }
}
