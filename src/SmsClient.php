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

    /**
     * @return array<string, mixed>
     */
    protected function handleResponse(Response $response): array
    {
        if ($response->failed()) {
            $message = $response->json('message');
            $errors = $response->json('errors');

            /** @var array<string, list<string>>|null $validatedErrors */
            $validatedErrors = null;

            if (is_array($errors)) {
                $isValid = true;

                foreach ($errors as $key => $value) {
                    if (! is_string($key) || ! is_array($value)) {
                        $isValid = false;
                        break;
                    }

                    foreach ($value as $error) {
                        if (! is_string($error)) {
                            $isValid = false;
                            break 2;
                        }
                    }
                }

                if ($isValid) {
                    /** @var array<string, list<string>> $errors */
                    $validatedErrors = $errors;
                }
            }

            throw new SmsException(
                message: is_string($message)
                    ? $message
                    : 'InexPhone API request failed.',
                status: $response->status(),
                errors: $validatedErrors,
            );
        }

        $data = $response->json();

        if (! is_array($data)) {
            return [];
        }

        /** @var array<string, mixed> $data */
        return $data;
    }

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

    /**
     * @return array<string, mixed>
     */
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

    /**
     * @param array<string, mixed> $params
     * @return array<string, mixed>
     */
    public function list(array $params = []): array
    {
        return $this->handleResponse(
            $this->http()->get('/sms', $params)
        );
    }

    /**
     * @return array<string, mixed>
     */
    public function find(string $uuid): array
    {
        return $this->handleResponse(
            $this->http()->get("/sms/{$uuid}")
        );
    }

    /**
     * @return array<string, mixed>
     */
    public function sendOtp(
        string $phone,
        string $subject,
        ?string $text = null,
        ?int $expiresIn = null,
        ?int $codeDigits = null,
    ): array {
        return $this->handleResponse(
            $this->http()->post('/otp/send', array_filter([
                'phone' => $phone,
                'subject' => $subject,
                'text' => $text,
                'expiresIn' => $expiresIn,
                'codeDigits' => $codeDigits,
            ], static fn (mixed $value): bool => $value !== null))
        );
    }

    
    /**
     * @return array<string, mixed>
     */
    public function verifyOtp(
        string $phone,
        string $code,
    ): array {
        return $this->handleResponse(
            $this->http()->post('/otp/verify', [
                'phone' => $phone,
                'code' => $code,
            ])
        );
    }

}