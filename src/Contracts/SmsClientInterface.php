<?php

namespace Inexphone\Sms\Contracts;

interface SmsClientInterface
{
    public function send(
        string $phone,
        string $subject,
        string $message,
        bool $ignoreBlacklist = false,
        ?string $submitCallbackUrl = null,
        ?string $deliveryCallbackUrl = null,
    ): array;

    public function sendCommercial(
        string $phone,
        string $subject,
        string $message,
    ): array;

    public function sendBulk(
        string $subject,
        string $message,
        array $phoneNumbers,
        ?string $submitCallbackUrl = null,
        ?string $deliveryCallbackUrl = null,
    ): array;

    public function list(array $params = []): array;

    public function find(string $uuid): array;
}