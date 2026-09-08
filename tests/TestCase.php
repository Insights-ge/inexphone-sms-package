<?php

namespace Tests;

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Events\EventServiceProvider;
use Illuminate\Filesystem\FilesystemServiceProvider;
use Illuminate\Foundation\Providers\FoundationServiceProvider;
use Illuminate\Log\LogServiceProvider;
use Illuminate\Translation\TranslationServiceProvider;
use Illuminate\Config\Repository;
use Illuminate\Support\Facades\Facade;
use Inexphone\Sms\SmsServiceProvider;

abstract class TestCase extends BaseTestCase
{
    public function createApplication(): Application
    {
        $app = new Application(
            basePath: dirname(__DIR__)
        );

        Facade::setFacadeApplication($app);

        $app->instance('config', new Repository([
            'inexphone-sms' => [
                'base_url' => 'https://smsservice.inexphone.ge/api/v1',
                'token' => 'test-token',
                'language' => 'ka',
                'timeout' => 30,
            ],
        ]));

        $app->register(EventServiceProvider::class);
        $app->register(FilesystemServiceProvider::class);
        $app->register(FoundationServiceProvider::class);
        $app->register(LogServiceProvider::class);
        $app->register(TranslationServiceProvider::class);
        $app->register(SmsServiceProvider::class);

        return $app;
    }
}