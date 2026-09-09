<?php

declare(strict_types=1);

namespace Inexphone\Sms\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static array<string, mixed> send(string $phone, string $subject, string $message, bool $ignoreBlacklist = false, ?string $submitCallbackUrl = null, ?string $deliveryCallbackUrl = null)
 * @method static array<string, mixed> sendCommercial(string $phone, string $subject, string $message)
 * @method static array<string, mixed> sendBulk(string $subject, string $message, array<int, string> $phoneNumbers, ?string $submitCallbackUrl = null, ?string $deliveryCallbackUrl = null)
 * @method static array<string, mixed> list(array<string, mixed> $params = [])
 * @method static array<string, mixed> find(string $uuid)
 */
class Sms extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \Inexphone\Sms\SmsClient::class;
    }
}