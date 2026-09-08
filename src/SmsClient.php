<?php

declare(strict_types=1);

namespace Inexphone\Sms;

use Illuminate\Http\Client\PendingRequest;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;
use Inexphone\Sms\Contracts\SmsClientInterface;
use Inexphone\Sms\Exceptions\SmsException;

class SmsClient implements SmsClientInterface
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

    protected function handleResponse(Response $response): array
    {
        if ($response->failed()) {
            throw new SmsException(
                message: $response->json('message', 'InexPhone API request failed.'),
                status: $response->status(),
                errors: $response->json('errors'),
            );
        }

        return $response->json();
    }

    public function send(
        string $phone,
        string $subject,
        string $message,
        bool $ignoreBlacklist = false,
        ?string $submitCallbackUrl = null,
        ?string $deliveryCallbackUrl = null,
    ): array {
        return $this->handleResponse(
            $this->http()->post('/sms/one', array_filter([
                'phone' => $phone,
                'subject' => $subject,
                'message' => $message,
                'ignore_blacklist' => $ignoreBlacklist,
                'submit_callback_url' => $submitCallbackUrl,
                'delivery_callback_url' => $deliveryCallbackUrl,
            ], static fn ($value) => $value !== null))
        );
    }

    public function sendCommercial(
        string $phone,
        string $subject,
        string $message,
    ): array {
        return $this->handleResponse(
            $this->http()->post('/sms/commercial', [
                'phone' => $phone,
                'subject' => $subject,
                'message' => $message,
            ])
        );
    }

    public function sendBulk(
        string $subject,
        string $message,
        array $phoneNumbers,
        ?string $submitCallbackUrl = null,
        ?string $deliveryCallbackUrl = null,
    ): array {
        return $this->handleResponse(
            $this->http()->post('/sms/bulk', array_filter([
                'subject' => $subject,
                'message' => $message,
                'phone_numbers' => $phoneNumbers,
                'submit_callback_url' => $submitCallbackUrl,
                'delivery_callback_url' => $deliveryCallbackUrl,
            ], static fn ($value) => $value !== null))
        );
    }

    public function list(array $params = []): array
    {
        return $this->handleResponse(
            $this->http()->get('/sms', $params)
        );
    }

    public function find(string $uuid): array
    {
        return $this->handleResponse(
            $this->http()->get("/sms/{$uuid}")
        );
    }
}