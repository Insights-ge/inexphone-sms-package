<?php

namespace Inexphone\Sms;

use Illuminate\Support\ServiceProvider;

class SmsServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(
            __DIR__ . '/../config/inexphone-sms.php',
            'inexphone-sms'
        );

        $this->app->singleton(SmsClient::class, function ($app) {
            return new SmsClient(
                baseUrl: $app['config']->get('inexphone-sms.base_url'),
                token: $app['config']->get('inexphone-sms.token'),
                language: $app['config']->get('inexphone-sms.language'),
                timeout: $app['config']->get('inexphone-sms.timeout'),
            );
        });
    }

    public function boot(): void
    {
        $this->publishes([
            __DIR__ . '/../config/inexphone-sms.php' => $this->app->configPath('inexphone-sms.php'),
        ], 'inexphone-sms-config');
    }
    
}