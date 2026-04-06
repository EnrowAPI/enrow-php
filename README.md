# Enrow PHP SDK

[![License: MIT](https://img.shields.io/badge/License-MIT-green.svg)](https://opensource.org/licenses/MIT)
[![GitHub stars](https://img.shields.io/github/stars/EnrowAPI/enrow-php)](https://github.com/EnrowAPI/enrow-php)
[![Last commit](https://img.shields.io/github/last-commit/EnrowAPI/enrow-php)](https://github.com/EnrowAPI/enrow-php/commits)

Find and verify professional emails, phone numbers, and contact information with the [Enrow API](https://enrow.io).

## Install

```bash
composer require enrow/enrow
```

## Quick start

```php
use Enrow\Enrow;

$enrow = new Enrow('your_api_key');

$result = $enrow->email->find([
    'company_domain' => 'apple.com',
    'fullname' => 'Tim Cook',
]);

echo $result['email']; // tcook@apple.com
```

## Email Finder

```php
$search = $enrow->email->find([
    'company_domain' => 'apple.com',
    'fullname' => 'Tim Cook',
    'settings' => ['country_code' => 'US'],
]);

$result = $enrow->email->get('search_abc123');

// Bulk
$batch = $enrow->email->findBulk([
    'searches' => [
        ['company_domain' => 'apple.com', 'fullname' => 'Tim Cook'],
        ['company_domain' => 'microsoft.com', 'fullname' => 'Satya Nadella'],
    ],
]);
$results = $enrow->email->getBulk($batch['batch_id']);
```

## Email Verifier

```php
$verification = $enrow->verify->single(['email' => 'tcook@apple.com']);
echo $verification['qualification']; // "valid"

$batch = $enrow->verify->bulk(['emails' => ['a@b.com', 'c@d.com']]);
$results = $enrow->verify->getBulk($batch['batch_id']);
```

## Phone Finder

```php
$phone = $enrow->phone->find(['linkedin_url' => 'https://linkedin.com/in/timcook']);
$result = $enrow->phone->get('phone_abc123');

$batch = $enrow->phone->findBulk(['searches' => [...]]);
$results = $enrow->phone->getBulk($batch['batch_id']);
```

## Reverse Email

```php
$person = $enrow->reverseEmail->find(['email' => 'tcook@apple.com']);
echo $person['first_name']; // "Tim"

$batch = $enrow->reverseEmail->findBulk(['emails' => [['email' => 'tcook@apple.com']]]);
$results = $enrow->reverseEmail->getBulk($batch['id']);
```

## Account

```php
$info = $enrow->account->info();
echo $info['credits']; // 8500
```

## Error handling

```php
use Enrow\Exceptions\RateLimitException;
use Enrow\Exceptions\InsufficientBalanceException;
use Enrow\Exceptions\EnrowException;

try {
    $enrow->email->find(['company_domain' => 'apple.com']);
} catch (RateLimitException $e) {
    // 429
} catch (InsufficientBalanceException $e) {
    // 422
} catch (EnrowException $e) {
    echo $e->status . ': ' . $e->getMessage();
}
```

## Credits

| Endpoint | Cost |
|----------|------|
| Email Finder | 1 credit/email |
| Email Verifier | 0.25 credit/email |
| Phone Finder | 50 credits/phone |
| Reverse Email | 5 credits/lookup |

## Links

- [API Documentation](https://docs.enrow.io)
- [Enrow](https://enrow.io)

## License

MIT
