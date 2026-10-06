<?php

namespace CaiqueBispo\BancoDoBrasil\Auth;

use CaiqueBispo\BancoDoBrasil\Contracts\TokenStorageInterface;
use CaiqueBispo\BancoDoBrasil\Enums\Environment;
use CaiqueBispo\BancoDoBrasil\Exceptions\AuthenticationException;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

class OAuthTokenManager
{
    private Client $http;

    private TokenStorageInterface $storage;

    public function __construct(
        ?TokenStorageInterface $storage = null,
    ) {
        $this->storage = $storage ?? new MemoryTokenStorage();
        $this->http = new Client(['timeout' => 15]);
    }

    public function getToken(
        string $clientId,
        string $clientSecret,
        Environment $environment,
        string $scope = '',
    ): string {
        $cacheKey = md5("{$clientId}:{$environment->value}:{$scope}");

        $cached = $this->storage->get($cacheKey);
        if ($cached) {
            return $cached['access_token'];
        }

        $token = $this->requestToken($clientId, $clientSecret, $environment, $scope);

        $ttl = ($token['expires_in'] ?? 600) - 30;
        $this->storage->set($cacheKey, $token, max($ttl, 10));

        return $token['access_token'];
    }

    private function requestToken(
        string $clientId,
        string $clientSecret,
        Environment $environment,
        string $scope,
    ): array {
        $url = $environment->oauthBaseUrl() . '/oauth/token';

        $params = [
            'grant_type' => 'client_credentials',
        ];

        if ($scope) {
            $params['scope'] = $scope;
        }

        try {
            $response = $this->http->post($url, [
                'auth' => [$clientId, $clientSecret],
                'form_params' => $params,
                'headers' => [
                    'Content-Type' => 'application/x-www-form-urlencoded',
                    'Cache-Control' => 'no-cache',
                ],
            ]);

            $body = json_decode($response->getBody()->getContents(), true);

            if (empty($body['access_token'])) {
                throw new AuthenticationException('Resposta OAuth sem access_token', 401);
            }

            return $body;
        } catch (RequestException $e) {
            $status = $e->hasResponse() ? $e->getResponse()->getStatusCode() : 0;
            $body = $e->hasResponse()
                ? (json_decode($e->getResponse()->getBody()->getContents(), true) ?? [])
                : [];

            throw AuthenticationException::fromResponse($status, array_merge($body, [
                'mensagem' => $body['error_description'] ?? $body['mensagem'] ?? 'Falha na autenticação OAuth',
            ]));
        }
    }
}
