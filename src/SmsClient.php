<?php

namespace Inexphone\Sms;

use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;

class SmsClient
{
    public function __construct(
        protected string $baseUrl,
        protected string $token,
        protected string $language = 'ka',
        protected int $timeout = 30,
    ) {}

    protected function http(): PendingRequest
    {
        return Http::baseUrl($this->baseUrl)
            ->withToken($this->token)
            ->acceptJson()
            ->withHeaders([
                'Accept-Language' => $this->language,
            ])
            ->timeout($this->timeout);
    }

    public function send(
        string $phone,
        string $subject,
        string $message,
        bool $ignoreBlacklist = false,
        ?string $submitCallbackUrl = null,
        ?string $deliveryCallbackUrl = null,
    ): array {
        return $this->http()
            ->post('/sms/one', [
                'phone' => $phone,
                'subject' => $subject,
                'message' => $message,
                'ignore_blacklist' => $ignoreBlacklist,
                'submit_callback_url' => $submitCallbackUrl,
                'delivery_callback_url' => $deliveryCallbackUrl,
            ])
            ->throw()
            ->json();
    }

    public function sendCommercial(
        string $phone,
        string $subject,
        string $message,
    ): array {
        return $this->http()
            ->post('/sms/commercial', [
                'phone' => $phone,
                'subject' => $subject,
                'message' => $message,
            ])
            ->throw()
            ->json();
    }

    public function sendBulk(
        string $subject,
        string $message,
        array $phoneNumbers,
        ?string $submitCallbackUrl = null,
        ?string $deliveryCallbackUrl = null,
    ): array {
        return $this->http()
            ->post('/sms/bulk', [
                'subject' => $subject,
                'message' => $message,
                'phone_numbers' => $phoneNumbers,
                'submit_callback_url' => $submitCallbackUrl,
                'delivery_callback_url' => $deliveryCallbackUrl,
            ])
            ->throw()
            ->json();
    }

    public function list(array $params = []): array
    {
        return $this->http()
            ->get('/sms', $params)
            ->throw()
            ->json();
    }

    public function find(string $uuid): array
    {
        return $this->http()
            ->get("/sms/{$uuid}")
            ->throw()
            ->json();
    }
}