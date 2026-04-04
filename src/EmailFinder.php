<?php

namespace Enrow;

class EmailFinder
{
    private HttpClient $http;

    public function __construct(HttpClient $http)
    {
        $this->http = $http;
    }

    public function find(array $params): array
    {
        return $this->http->post('/email/find/single', $params);
    }

    public function get(string $id): array
    {
        return $this->http->get("/email/find/single/{$id}");
    }

    public function findBulk(array $params): array
    {
        return $this->http->post('/email/find/bulk', $params);
    }

    public function getBulk(string $id): array
    {
        return $this->http->get("/email/find/bulk/{$id}");
    }
}
