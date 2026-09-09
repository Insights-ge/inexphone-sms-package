<?php

declare(strict_types=1);

namespace Inexphone\Sms\Contracts;

interface SmsClientInterface
{
    /**
     * @return array<string, mixed>
     */
    public function send(
        string $phone,
        string $subject,
        string $message,
        bool $ignoreBlacklist = false,
        ?string $submitCallbackUrl = null,
        ?string $deliveryCallbackUrl = null,
    ): array;

    /**
     * @return array<string, mixed>
     */
    public function sendCommercial(
        string $phone,
        string $subject,
        string $message,
    ): array;

    /**
     * @param array<int, string> $phoneNumbers
     * @return array<string, mixed>
     */
    public function sendBulk(
        string $subject,
        string $message,
        array $phoneNumbers,
        ?string $submitCallbackUrl = null,
        ?string $deliveryCallbackUrl = null,
    ): array;

    /**
     * @param array<string, mixed> $params
     * @return array<string, mixed>
     */
    public function list(array $params = []): array;

    /**
     * @return array<string, mixed>
     */
    public function find(string $uuid): array;
}