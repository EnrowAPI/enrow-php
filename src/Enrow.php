<?php

namespace Enrow;

class Enrow
{
    public EmailFinder $email;
    public EmailVerifier $verify;
    public PhoneFinder $phone;
    public ReverseEmail $reverseEmail;
    public Account $account;

    public function __construct(string $apiKey, ?string $baseUrl = null)
    {
        $http = new HttpClient($apiKey, $baseUrl);

        $this->email = new EmailFinder($http);
        $this->verify = new EmailVerifier($http);
        $this->phone = new PhoneFinder($http);
        $this->reverseEmail = new ReverseEmail($http);
        $this->account = new Account($http);
    }
}
