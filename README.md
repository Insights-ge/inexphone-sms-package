<p align="center">
  <a href="https://insights.ge" target="_blank" rel="noopener noreferrer">
    <picture>
      <source media="(prefers-color-scheme: dark)" srcset="public/images/logo-light.avif">
      <source media="(prefers-color-scheme: light)" srcset="public/images/logo-dark.avif">
      <img src="public/images/logo-light.avif" alt="Insights Logo" width="180">
    </picture>
  </a>
</p>

<h1 align="center">Laravel InexPhone SMS</h1>

<p align="center">
  <strong>A production-ready Laravel package for seamless integration with the InexPhone SMS API.</strong>
</p>

<p align="center">
  <a href="https://insights.ge" target="_blank" rel="noopener noreferrer">
    <img src="https://img.shields.io/badge/Website-insights.ge-007ACC?style=for-the-badge&logo=google-chrome&logoColor=white" alt="Insights Website">
  </a>
  <a href="https://laravel.com" target="_blank" rel="noopener noreferrer">
    <img src="https://img.shields.io/badge/Laravel-11%20%7C%2012%20%7C%2013-FF2D20?style=for-the-badge&logo=laravel&logoColor=white" alt="Laravel 11, 12 and 13">
  </a>
  <a href="https://www.php.net" target="_blank" rel="noopener noreferrer">
    <img src="https://img.shields.io/badge/PHP-8.2%2B-777BB4?style=for-the-badge&logo=php&logoColor=white" alt="PHP 8.2+">
  </a>
  <a href="LICENSE">
    <img src="https://img.shields.io/badge/License-MIT-green.svg?style=for-the-badge" alt="License MIT">
  </a>
</p>

<p align="center">
  <a href="#-about-us--insights">About Insights</a> •
  <a href="#-key-features">Key Features</a> •
  <a href="#-requirements">Requirements</a> •
  <a href="#-installation">Installation</a> •
  <a href="#-configuration">Configuration</a> •
  <a href="#-usage">Usage</a> •
  <a href="#-error-handling">Error Handling</a> •
  <a href="#-testing">Testing</a>
</p>

---

## 💡 About Us & Insights

**Laravel InexPhone SMS** was created by the team at [**Insights**](https://insights.ge) to provide a clean, reliable, and Laravel-native way to integrate the **InexPhone SMS API** into modern Laravel applications.

Instead of repeatedly implementing HTTP authentication, request handling, SMS payloads, OTP verification, callbacks, blacklist access, API responses, and error handling for every Laravel project, this package provides a reusable integration built around Laravel's conventions and developer experience.

> *"Integrate once. Send with confidence. Build more."* — **PrayerPosition**

### Why Laravel InexPhone SMS?

* ⚡ **Zero Integration Friction** — Install the package with Composer and start using the InexPhone API through a simple Laravel API.
* 📱 **Complete SMS Support** — Send single, commercial, and bulk SMS messages.
* 🔐 **OTP Support** — Send one-time passwords and verify OTP codes through the InexPhone API.
* 🚫 **Blacklist Access** — Retrieve blacklist records and inspect individual blacklist entries.
* 🔄 **Built-in Callbacks** — Configure submit and delivery callback URLs for supported SMS requests.
* 🔐 **Secure Configuration** — API credentials and configuration are managed through Laravel's environment and configuration system.
* 🎯 **Laravel-Native Experience** — Automatic service provider discovery, service-container bindings, and a convenient facade.
* 🛡️ **Reliable Error Handling** — API failures are represented by a dedicated `SmsException`, giving applications access to HTTP status codes and API error details.
* 🧪 **Quality First** — The package is covered by automated PHPUnit tests and static analysis with PHPStan/Larastan.

---

## ✨ Key Features

* 📤 **Single SMS** — Send individual SMS messages through the InexPhone API.
* 📢 **Commercial SMS** — Send commercial SMS messages.
* 📱 **Bulk SMS** — Send the same message to multiple phone numbers.
* 📋 **SMS Listing** — Retrieve previously sent SMS messages.
* 🔎 **SMS Lookup** — Retrieve a specific SMS using its UUID.
* 🔐 **OTP Sending** — Send one-time password codes to a phone number.
* ✅ **OTP Verification** — Verify an OTP code for a phone number.
* 🚫 **Blacklist Listing** — Retrieve blacklist records with pagination and filtering.
* 🔎 **Blacklist Lookup** — Retrieve a specific blacklist record by ID.
* 🔄 **Submit Callbacks** — Receive events related to SMS submission.
* 📬 **Delivery Callbacks** — Receive SMS delivery status events.
* 🚫 **Blacklist Control** — Optionally ignore blacklist restrictions for supported SMS requests.
* 🌐 **Language Support** — Configure the InexPhone API language.
* 🔐 **Bearer Authentication** — Secure API authentication using your InexPhone token.
* ⚙️ **Configurable Requests** — Configure the API URL and HTTP timeout through Laravel configuration.
* 🧩 **Laravel Package Discovery** — No manual service provider registration required.
* 🎯 **Facade Support** — Use the convenient `Sms` facade throughout your application.
* ❌ **Dedicated Exceptions** — Handle API errors using `SmsException`.
* 🧪 **Automated Testing** — Core functionality is covered by PHPUnit tests.
* 🔍 **Static Analysis** — The package is checked using PHPStan/Larastan.

---

## 📋 Requirements

* **PHP**: `^8.2`
* **Laravel**: `11.x`, `12.x`, or `13.x`
* **Composer**: `^2.0`
* An active **InexPhone SMS API token**

---

## ⚡ Installation

Install the package using Composer:

```bash
composer require insightsge/laravel-inexphone-sms
```

The package service provider is automatically registered through Laravel package discovery.

No manual provider registration is required.

---

## ⚙️ Configuration

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
| :----------------------- | :------------------------------ | :--------------------------------------- |
| `INEXPHONE_SMS_BASE_URL` | InexPhone API base URL          | `https://smsservice.inexphone.ge/api/v1` |
| `INEXPHONE_SMS_TOKEN`    | InexPhone API bearer token      | —                                        |
| `INEXPHONE_SMS_LANGUAGE` | API language (`ka` or `en`)     | `ka`                                     |
| `INEXPHONE_SMS_TIMEOUT`  | HTTP request timeout in seconds | `30`                                     |

> 🔐 **Security:** Never commit your actual `INEXPHONE_SMS_TOKEN` to source control. Store your API token in your environment configuration.

---

## 🚀 Usage

Import the SMS facade:

```php
use Inexphone\Sms\Facades\Sms;
```

---

### 📤 Send a Single SMS

Send a basic SMS message:

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

---

### 📢 Send a Commercial SMS

```php
$response = Sms::sendCommercial(
    phone: '995591950549',
    subject: 'Special Offer',
    message: 'Check out our latest offer!',
);
```

---

### 📱 Send Bulk SMS

Send the same message to multiple phone numbers:

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

---

### 🔐 Send an OTP

Send a one-time password to a phone number:

```php
$response = Sms::sendOtp(
    phone: '995591950549',
    subject: 'idrive',
);
```

The InexPhone API generates the OTP code automatically.

The optional parameters can be used to customize the OTP message, expiration time, and code length:

```php
$response = Sms::sendOtp(
    phone: '995591950549',
    subject: 'idrive',
    text: 'Your verification code is: {{CODE}}',
    expiresIn: 120,
    codeDigits: 6,
);
```

### `sendOtp()` Parameters

| Parameter    | Type      | Required | Description                                                              |
| :----------- | :-------- | :------- | :----------------------------------------------------------------------- |
| `phone`      | `string`  | Yes      | Phone number that should receive the OTP.                                |
| `subject`    | `string`  | Yes      | Registered/allowed InexPhone SMS subject.                                |
| `text`       | `?string` | No       | OTP message text. Use `{{CODE}}` where the generated code should appear. |
| `expiresIn`  | `?int`    | No       | OTP expiration time in seconds.                                          |
| `codeDigits` | `?int`    | No       | Number of digits in the generated OTP code.                              |

If the optional parameters are not provided, the InexPhone API applies its defaults.

Default values provided by the API include:

```text
Message:     Your verification code is: {{CODE}}
Expiration:  60 seconds
Code length: 4 digits
```

The API returns the generated OTP operation information and a success message.

Example response:

```php
[
    'message' => 'OTP sent successfully.',
    'data' => [
        // API response data
    ],
]
```

> **Note:** The `subject` must be an allowed/registered subject in your InexPhone account. The API may reject subjects that are not permitted.

---

### ✅ Verify an OTP

After the user enters the OTP code they received, verify it:

```php
$response = Sms::verifyOtp(
    phone: '995591950549',
    code: '1552',
);
```

A successful verification returns:

```php
[
    'message' => 'ok',
    'data' => [
        'id' => null,
        'type' => 'object',
        'attributes' => [],
    ],
]
```

### `verifyOtp()` Parameters

| Parameter | Type     | Required | Description                           |
| :-------- | :------- | :------- | :------------------------------------ |
| `phone`   | `string` | Yes      | Phone number associated with the OTP. |
| `code`    | `string` | Yes      | OTP code entered by the user.         |

The verification request only requires:

```json
{
    "phone": "995551563555",
    "code": "0123"
}
```

---

## 📋 List SMS Messages

Retrieve previously sent SMS messages:

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

### Supported Parameters

| Parameter           | Description                |
| :------------------ | :------------------------- |
| `page`              | Page number                |
| `perPage`           | Number of records per page |
| `sort`              | Sort order                 |
| `filters.subject`   | Filter by subject          |
| `filters.type`      | Filter by SMS type         |
| `filters.state`     | Filter by SMS state        |
| `filters.number`    | Filter by phone number     |
| `filters.dateStart` | Filter by start date       |
| `filters.dateEnd`   | Filter by end date         |

### Supported Sort Values

```text
-createDate
+createDate
-subject
+subject
```

---

## 🔎 Find an SMS

Retrieve a specific SMS by UUID:

```php
$response = Sms::find('sms-uuid');
```

---

## 🚫 List Blacklist Records

Retrieve blacklist records from InexPhone:

```php
$response = Sms::blacklists();
```

The response contains the blacklist records together with pagination metadata:

```php
[
    'data' => [],
    'meta' => [
        'pagination' => [
            'total' => 0,
            'count' => 0,
            'perPage' => 200,
            'currentPage' => 1,
            'totalPages' => 1,
            'links' => [
                'next' => null,
                'previous' => null,
            ],
        ],
    ],
    'message' => 'ok',
]
```

You can also provide pagination and filters:

```php
$response = Sms::blacklists([
    'page' => 1,
    'perPage' => 20,
    'filters' => [
        'keywords' => '555',
        'subjects' => 'idrive',
        'number' => '995591950549',
        'dateEnd' => '11/09/2026',
    ],
]);
```

### `blacklists()` Parameters

| Parameter           | Type     | Description                             |
| :------------------ | :------- | :-------------------------------------- |
| `page`              | `int`    | Page number.                            |
| `perPage`           | `int`    | Number of records per page.             |
| `filters[keywords]` | `string` | Filter by number, message, or comment.  |
| `filters[subjects]` | `string` | Comma-separated subjects for filtering. |
| `filters[number]`   | `string` | Filter by phone number.                 |
| `filters[dateEnd]`  | `string` | End date filter in `d/m/Y` format.      |

Example:

```php
$response = Sms::blacklists([
    'page' => 1,
    'perPage' => 10,
    'filters' => [
        'keywords' => '555',
        'subjects' => 'idrive',
    ],
]);
```

---

## 🔎 Find a Blacklist Record

Retrieve a specific blacklist record by its ID:

```php
$response = Sms::findBlacklist('blacklist-id');
```

A blacklist record can contain information such as the phone number, subject, message, comment, creation date, update date, and blacklist metadata.

Example response:

```php
[
    'message' => 'ok',
    'data' => [
        'id' => 'blacklist-id',
        'type' => 'blacklists',
        'attributes' => [
            'number' => '995591950549',
            'subject' => 'idrive',
            'message' => 'Example message',
            'comment' => 'Blocked number',
            'createdAt' => '...',
            'updatedAt' => '...',
            'deletedAt' => null,
        ],
    ],
]
```

> **Note:** The package currently supports retrieving blacklist records through the documented InexPhone API endpoints. Adding or removing blacklist records is not included because the provided API documentation does not expose create or delete blacklist endpoints.

---

## 🔄 Callbacks

The package supports callback URLs for SMS submission and delivery events.

### Submit Callback

```php
$response = Sms::send(
    phone: '995591950549',
    subject: 'Test',
    message: 'Hello from Laravel!',
    submitCallbackUrl: 'https://example.com/submit',
);
```

### Delivery Callback

```php
$response = Sms::send(
    phone: '995591950549',
    subject: 'Test',
    message: 'Hello from Laravel!',
    deliveryCallbackUrl: 'https://example.com/delivery',
);
```

### Both Callbacks

```php
$response = Sms::send(
    phone: '995591950549',
    subject: 'Test',
    message: 'Hello from Laravel!',
    submitCallbackUrl: 'https://example.com/submit',
    deliveryCallbackUrl: 'https://example.com/delivery',
);
```

Callbacks are currently available for the SMS sending methods that support them. OTP requests do not require callback URLs.

---

## 📚 Available Methods

| Method                  | Description                          |
| :---------------------- | :----------------------------------- |
| `Sms::send()`           | Send a single SMS                    |
| `Sms::sendCommercial()` | Send a commercial SMS                |
| `Sms::sendBulk()`       | Send SMS to multiple phone numbers   |
| `Sms::list()`           | Retrieve SMS messages                |
| `Sms::find()`           | Retrieve a specific SMS              |
| `Sms::sendOtp()`        | Send a one-time password             |
| `Sms::verifyOtp()`      | Verify a one-time password           |
| `Sms::blacklists()`     | Retrieve blacklist records           |
| `Sms::findBlacklist()`  | Retrieve a specific blacklist record |

---

## ❌ Error Handling

API errors are represented by the package's `SmsException` class.

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

The same exception handling applies to OTP and blacklist requests:

```php
use Inexphone\Sms\Exceptions\SmsException;

try {
    $response = Sms::findBlacklist('blacklist-id');
} catch (SmsException $exception) {
    $status = $exception->status;
    $errors = $exception->errors;

    // Handle the error...
}
```

The exception provides:

* `status` — HTTP status code returned by the InexPhone API.
* `errors` — API validation or error details when available.
* Exception message — The error message returned by the API.

### Example Error Response

```json
{
    "message": "Validation failed",
    "errors": {
        "general": [
            "Invalid phone number"
        ]
    }
}
```

For example, the API may return validation errors when supplied parameters are invalid.

---

## 🧪 Testing

Run the PHPUnit test suite:

```bash
vendor/bin/phpunit
```

Run static analysis with PHPStan/Larastan:

```bash
vendor/bin/phpstan analyse
```

The package uses Laravel's HTTP testing tools to test API interactions without making real API requests during the automated test suite.

The test suite covers:

* Single SMS sending
* Commercial SMS
* Bulk messaging
* SMS listing
* SMS lookup
* Blacklist listing
* Blacklist filtering
* Blacklist lookup
* OTP sending
* OTP sending with optional parameters
* OTP verification
* OTP validation errors
* API errors
* Callback URLs
* Request headers
* Authentication
* Configured language
* Default language
* Filtering
* Pagination
* Facade resolution
* Service-container bindings
* Optional parameter handling

Before submitting changes, make sure both the PHPUnit test suite and static analysis pass successfully.

---

## 📦 Package Structure

```text
laravel-inexphone-sms/
├── config/
│   └── inexphone-sms.php
├── src/
│   ├── Contracts/
│   │   └── SmsClientInterface.php
│   ├── Exceptions/
│   │   └── SmsException.php
│   ├── Facades/
│   │   └── Sms.php
│   ├── SmsClient.php
│   └── SmsServiceProvider.php
├── tests/
│   └── Feature/
│       └── SmsClientTest.php
├── composer.json
├── phpunit.xml
├── phpstan.neon
├── LICENSE
└── README.md
```

---

## 🤝 Contributing

Contributions, bug reports, and feature requests are welcome.

Before submitting a pull request, please make sure that:

* All tests pass.
* Static analysis passes.
* The code follows the existing project conventions.
* New functionality includes appropriate tests.
* Documentation is updated when public functionality changes.

---

## 📄 License

The **Laravel InexPhone SMS** package is open-sourced software licensed under the [MIT License](LICENSE).

---

<p align="center">
  Crafted with ❤️ by <a href="https://github.com/prayerposition" target="_blank" rel="noopener noreferrer"><strong>PrayerPosition</strong></a>
</p>

<p align="center">
  <sub>Laravel InexPhone SMS — Simple, reliable, and Laravel-friendly InexPhone SMS integration.</sub>
</p>
