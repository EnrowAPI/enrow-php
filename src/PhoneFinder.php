<?php

namespace Enrow;

class PhoneFinder
{
    private HttpClient $http;

    public function __construct(HttpClient $http)
    {
        $this->http = $http;
    }

    public function find(array $params): array
    {
        return $this->http->post('/phone/single', $params);
    }

    public function get(string $id): array
    {
        return $this->http->get('/phone/single', ['id' => $id]);
    }

    public function findBulk(array $params): array
    {
        return $this->http->post('/phone/bulk', $params);
    }

    public function getBulk(string $id): array
    {
        return $this->http->get('/phone/bulk', ['id' => $id]);
    }
}
