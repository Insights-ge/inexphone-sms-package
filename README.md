# Laravel InexPhone SMS

A Laravel package for integrating the InexPhone SMS API into Laravel applications.

## Requirements

* PHP 8.2 or higher
* Laravel 11, 12, or 13

## Installation

Install the package using Composer:

```bash
composer require prayerposition/laravel-inexphone-sms
```

The package service provider is automatically registered through Laravel package discovery.

## Configuration

Publish the package configuration file:

```bash
php artisan vendor:publish --tag=inexphone-sms-config
```

Add the following variables to your `.env` file:

```env
INEXPHONE_SMS_BASE_URL=https://smsservice.inexphone.ge/api/v1
INEXPHONE_SMS_TOKEN=your-api-token
INEXPHONE_SMS_LANGUAGE=ka
INEXPHONE_SMS_TIMEOUT=30
```

### Configuration Options

| Variable                 | Description                     | Default                                  |
| ------------------------ | ------------------------------- | ---------------------------------------- |
| `INEXPHONE_SMS_BASE_URL` | InexPhone API base URL          | `https://smsservice.inexphone.ge/api/v1` |
| `INEXPHONE_SMS_TOKEN`    | InexPhone API bearer token      | —                                        |
| `INEXPHONE_SMS_LANGUAGE` | API language (`ka` or `en`)     | `ka`                                     |
| `INEXPHONE_SMS_TIMEOUT`  | HTTP request timeout in seconds | `30`                                     |

## Usage

Import the SMS facade:

```php
use Inexphone\Sms\Facades\Sms;
```

### Send a Single SMS

```php
$response = Sms::send(
    phone: '995591950549',
    subject: 'Test',
    message: 'Hello from Laravel!',
);
```

You can also provide callback URLs and control blacklist behavior:

```php
$response = Sms::send(
    phone: '995591950549',
    subject: 'Test',
    message: 'Hello from Laravel!',
    ignoreBlacklist: true,
    submitCallbackUrl: 'https://example.com/submit',
    deliveryCallbackUrl: 'https://example.com/delivery',
);
```

### Send a Commercial SMS

```php
$response = Sms::sendCommercial(
    phone: '995591950549',
    subject: 'Special Offer',
    message: 'Check out our latest offer!',
);
```

### Send Bulk SMS

```php
$response = Sms::sendBulk(
    subject: 'Announcement',
    message: 'Important announcement.',
    phoneNumbers: [
        '995591111111',
        '995592222222',
    ],
);
```

Bulk SMS also supports callback URLs:

```php
$response = Sms::sendBulk(
    subject: 'Announcement',
    message: 'Important announcement.',
    phoneNumbers: [
        '995591111111',
        '995592222222',
    ],
    submitCallbackUrl: 'https://example.com/submit',
    deliveryCallbackUrl: 'https://example.com/delivery',
);
```

### List SMS Messages

Retrieve SMS messages:

```php
$response = Sms::list();
```

You can also pass pagination, sorting, and filters:

```php
$response = Sms::list([
    'page' => 1,
    'perPage' => 15,
    'sort' => '-createDate',
    'filters' => [
        'subject' => 'Test',
        'type' => 'transactional',
        'state' => 'delivered',
        'number' => '995591111111',
        'dateStart' => '01/09/2026',
        'dateEnd' => '08/09/2026',
    ],
]);
```

### Find an SMS

Retrieve a specific SMS by UUID:

```php
$response = Sms::find('sms-uuid');
```

## Error Handling

API errors are represented by `SmsException`.

```php
use Inexphone\Sms\Exceptions\SmsException;

try {
    $response = Sms::send(
        phone: '995591950549',
        subject: 'Test',
        message: 'Hello',
    );
} catch (SmsException $exception) {
    $status = $exception->status;
    $errors = $exception->errors;

    // Handle the error...
}
```

The exception provides:

* `status` — HTTP status code returned by the API
* `errors` — API validation or error details when available
* Exception message — API error message

## Available Methods

| Method                  | Description                        |
| ----------------------- | ---------------------------------- |
| `Sms::send()`           | Send a single SMS                  |
| `Sms::sendCommercial()` | Send a commercial SMS              |
| `Sms::sendBulk()`       | Send SMS to multiple phone numbers |
| `Sms::list()`           | Retrieve SMS messages              |
| `Sms::find()`           | Retrieve a specific SMS            |

## Testing

Run the PHPUnit test suite:

```bash
vendor/bin/phpunit
```

The package includes automated tests covering:

* SMS sending
* Commercial SMS
* Bulk messaging
* Callback URLs
* API errors
* Request headers
* Configuration
* Filtering and pagination
* Facade resolution
* Service-container bindings

## License

This package is open-sourced software licensed under the [MIT license](LICENSE).
