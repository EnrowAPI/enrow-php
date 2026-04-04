<?php

namespace Enrow;

class Account
{
    private HttpClient $http;

    public function __construct(HttpClient $http)
    {
        $this->http = $http;
    }

    public function info(): array
    {
        return $this->http->get('/account/info');
    }
}
