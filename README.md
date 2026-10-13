# Enrow PHP SDK

[![License: MIT](https://img.shields.io/badge/License-MIT-green.svg)](https://opensource.org/licenses/MIT)
[![GitHub stars](https://img.shields.io/github/stars/EnrowAPI/enrow-php)](https://github.com/EnrowAPI/enrow-php)
[![Last commit](https://img.shields.io/github/last-commit/EnrowAPI/enrow-php)](https://github.com/EnrowAPI/enrow-php/commits)

Find and verify professional emails and find phone numbers with the [Enrow API](https://enrow.io).

## Install

Requires PHP 8.1 or later. The SDK is not published on Packagist: install it from this GitHub repository.

```bash
composer config repositories.enrow vcs https://github.com/EnrowAPI/enrow-php
composer require enrow/enrow:dev-main
```

## Quick start

Every search is asynchronous: the call that starts it returns an `id`, and the result comes from the matching `get` method (or from a webhook). Responses are PHP arrays decoded from the API's JSON.

```php
<?php

require __DIR__ . '/vendor/autoload.php';

use Enrow\Enrow;

$enrow = new Enrow('YOUR_API_KEY');

$search = $enrow->email->find([
    'fullname' => 'Dwight Schrute',
    'company_domain' => 'dundermifflin.com',
]);

echo $search['id']; // "0cf517bc-16e8-45bc-b967-ab9116b3c804"

// Later, once the search is finished
$result = $enrow->email->get($search['id']);

if ($result['qualification'] === 'valid') {
    echo $result['email']; // "dwight.schrute@dundermifflin.com"
}
```

While a search is running, its `qualification` is `ongoing`. To skip polling, pass a URL in `settings.webhook`: Enrow calls it as soon as the search is finished. See [How webhooks work](https://docs.enrow.io/how-webhooks-work).

## Email Finder

```php
// From a full name and a company: company_domain or company_name
$search = $enrow->email->find([
    'fullname' => 'Dwight Schrute',
    'company_name' => 'Dunder Mifflin',
    'custom' => ['crm_id' => 'lead_001'],
    'settings' => [
        'country_code' => 'US',
        'retrieve_gender' => true,
        'webhook' => 'https://your-app.com/webhooks/enrow',
    ],
]);

// From a LinkedIn profile URL
$search = $enrow->email->find([
    'linkedin_url' => 'https://www.linkedin.com/in/dwight-schrute',
]);

$result = $enrow->email->get($search['id']);
// $result['qualification']: "ongoing", "valid" (email found) or "invalid" (not found)

// Bulk: up to 5,000 searches per batch
$batch = $enrow->email->findBulk([
    'searches' => [
        ['fullname' => 'Dwight Schrute', 'company_domain' => 'dundermifflin.com', 'custom' => 'lead_001'],
        ['fullname' => 'Jim Halpert', 'company_domain' => 'dundermifflin.com', 'custom' => 'lead_002'],
    ],
    'settings' => ['webhook' => 'https://your-app.com/webhooks/enrow'],
]);

$results = $enrow->email->getBulk($batch['id']);
// $results['general']['status']: "ongoing", "completed" or "failed"
// $results['results']: one entry per search once the batch is completed
```

A search from a LinkedIn profile URL works for single searches only. See [Search from a LinkedIn profile URL](https://docs.enrow.io/api-reference/email-finder/find-single#search-from-a-linkedin-profile-url) for the accepted URLs and what it costs.

## Email Verifier

```php
$verification = $enrow->verify->single(['email' => 'pam.beesly@dundermifflin.com']);

$result = $enrow->verify->get($verification['id']);
// $result['qualification']: "ongoing", "valid" (deliverable) or "invalid"

// Bulk: up to 5,000 emails per batch
$batch = $enrow->verify->bulk([
    'verifications' => [
        'pam.beesly@dundermifflin.com',
        'angela.martin@dundermifflin.com',
    ],
]);

$results = $enrow->verify->getBulk($batch['id']);
```

## Phone Finder

```php
// From a LinkedIn profile URL (recommended)
$search = $enrow->phone->find([
    'linkedin_url' => 'https://www.linkedin.com/in/michael-scott/',
]);

// Or from a name and a company: company_domain or company_name
$search = $enrow->phone->find([
    'firstname' => 'Michael',
    'lastname' => 'Scott',
    'company_domain' => 'dundermifflin.com',
]);

$result = $enrow->phone->get($search['id']);
// $result['qualification']: "ongoing", "found" or "not_found"
// once found: $result['number'] and $result['country']

// Bulk: up to 3,000 searches per batch
$batch = $enrow->phone->findBulk([
    'searches' => [
        ['linkedin_url' => 'https://www.linkedin.com/in/michael-scott/'],
        ['linkedin_url' => 'https://www.linkedin.com/in/dwight-schrute/'],
    ],
]);

$results = $enrow->phone->getBulk($batch['id']);
```

When a search has both a LinkedIn URL and a name, the LinkedIn URL wins.

## Account

```php
$info = $enrow->account->info();
echo $info['credits']; // 8500
```

## Error handling

```php
use Enrow\Exceptions\AuthenticationException;
use Enrow\Exceptions\EnrowException;
use Enrow\Exceptions\InsufficientBalanceException;
use Enrow\Exceptions\RateLimitException;

try {
    $enrow->email->find([
        'fullname' => 'Dwight Schrute',
        'company_domain' => 'dundermifflin.com',
    ]);
} catch (AuthenticationException $e) {
    // 401: missing or invalid API key
} catch (InsufficientBalanceException $e) {
    // 402: not enough credits
} catch (RateLimitException $e) {
    // 429: more than 10 requests per second, retry with a backoff
} catch (EnrowException $e) {
    // Any other API error, such as a 400 for an invalid payload
    echo $e->status . ': ' . $e->getMessage();
}
```

Every exception extends `Enrow\Exceptions\EnrowException`: `$e->status` holds the HTTP status and `$e->getMessage()` the message sent by the API.

## Credits

| Endpoint | Cost |
|----------|------|
| Email Finder | 1 credit per email found |
| Email Verifier | 0.25 credit per check |
| Phone Finder | 40 credits per phone found (50 on legacy plans) |

See [Credits & billing](https://docs.enrow.io/credits-billing) for the details.

## Links

- [API Documentation](https://docs.enrow.io)
- [Enrow](https://enrow.io)

## License

MIT
