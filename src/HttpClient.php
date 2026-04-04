<?php

namespace Enrow;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Enrow\Exceptions\EnrowException;
use Enrow\Exceptions\AuthenticationException;
use Enrow\Exceptions\InsufficientBalanceException;
use Enrow\Exceptions\RateLimitException;

class HttpClient
{
    private Client $client;

    public function __construct(string $apiKey, ?string $baseUrl = null)
    {
        $this->client = new Client([
            'base_uri' => $baseUrl ?? 'https://api.enrow.io',
            'headers' => [
                'x-api-key' => $apiKey,
                'Content-Type' => 'application/json',
            ],
        ]);
    }

    public function get(string $path, array $query = []): array
    {
        try {
            $options = [];
            if (!empty($query)) {
                $options['query'] = $query;
            }
            $response = $this->client->get($path, $options);
            return json_decode($response->getBody()->getContents(), true);
        } catch (RequestException $e) {
            $this->handleError($e);
        }
    }

    public function post(string $path, array $body): array
    {
        try {
            $response = $this->client->post($path, ['json' => $body]);
            return json_decode($response->getBody()->getContents(), true);
        } catch (RequestException $e) {
            $this->handleError($e);
        }
    }

    private function handleError(RequestException $e): never
    {
        $status = $e->getResponse() ? $e->getResponse()->getStatusCode() : 500;
        $body = $e->getResponse() ? json_decode($e->getResponse()->getBody()->getContents(), true) : [];
        $message = $body['message'] ?? $e->getMessage();
        $error = $body['error'] ?? 'UnknownError';

        match ($status) {
            401 => throw new AuthenticationException($message),
            422 => throw new InsufficientBalanceException($message),
            429 => throw new RateLimitException($message),
            default => throw new EnrowException($message, $status, $error),
        };
    }
}
