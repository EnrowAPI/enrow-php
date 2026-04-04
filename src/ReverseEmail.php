<?php

namespace Enrow;

class ReverseEmail
{
    private HttpClient $http;

    public function __construct(HttpClient $http)
    {
        $this->http = $http;
    }

    public function find(array $params): array
    {
        return $this->http->post('/reverse-email/single', $params);
    }

    public function get(string $id): array
    {
        return $this->http->get("/reverse-email/single/{$id}");
    }

    public function findBulk(array $params): array
    {
        return $this->http->post('/reverse-email/bulk', $params);
    }

    public function getBulk(string $id): array
    {
        return $this->http->get("/reverse-email/bulk/{$id}");
    }
}
