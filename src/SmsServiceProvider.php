<?php

declare(strict_types=1);

namespace Inexphone\Sms;

use Illuminate\Support\ServiceProvider;
use Inexphone\Sms\Contracts\SmsClientInterface;

class SmsServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(
            __DIR__ . '/../config/inexphone-sms.php',
            'inexphone-sms'
        );

        $this->app->singleton(SmsClient::class, function () {
            $baseUrl = config('inexphone-sms.base_url');
            $token = config('inexphone-sms.token');
            $language = config('inexphone-sms.language', 'ka');
            $timeout = config('inexphone-sms.timeout', 30);

            if (! is_string($baseUrl)) {
                throw new \UnexpectedValueException(
                    'The InexPhone SMS base URL must be a string.'
                );
            }

            if (! is_string($token)) {
                throw new \UnexpectedValueException(
                    'The InexPhone SMS token must be a string.'
                );
            }

            if (! is_string($language)) {
                throw new \UnexpectedValueException(
                    'The InexPhone SMS language must be a string.'
                );
            }

            if (! is_int($timeout)) {
                throw new \UnexpectedValueException(
                    'The InexPhone SMS timeout must be an integer.'
                );
            }

            return new SmsClient(
                baseUrl: $baseUrl,
                token: $token,
                language: $language,
                timeout: $timeout,
            );
        });

        $this->app->bind(
            SmsClientInterface::class,
            SmsClient::class
        );
    }

    public function boot(): void
    {
        $this->publishes([
            __DIR__ . '/../config/inexphone-sms.php' => $this->app->configPath('inexphone-sms.php'),
        ], 'inexphone-sms-config');
    }
    
}