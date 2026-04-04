<?php

namespace Enrow;

class EmailVerifier
{
    private HttpClient $http;

    public function __construct(HttpClient $http)
    {
        $this->http = $http;
    }

    public function single(array $params): array
    {
        return $this->http->post('/email/verify/single', $params);
    }

    public function get(string $id): array
    {
        return $this->http->get('/email/verify/single', ['id' => $id]);
    }

    public function bulk(array $params): array
    {
        return $this->http->post('/email/verify/bulk', $params);
    }

    public function getBulk(string $id): array
    {
        return $this->http->get('/email/verify/bulk', ['id' => $id]);
    }
}
