<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Http;
use Inexphone\Sms\Facades\Sms;
use Tests\TestCase;

class SmsClientTest extends TestCase
{
    public function test_it_sends_a_single_sms(): void
    {
        Http::fake([
            'https://smsservice.inexphone.ge/api/v1/sms/one' => Http::response([
                'message' => 'SMS submitted successfully.',
                'data' => [
                    'id' => 'test-uuid',
                ],
            ], 201),
        ]);

        $response = Sms::send(
            phone: '995591950549',
            subject: 'Test',
            message: 'Hello from package',
        );

        $this->assertSame(
            'SMS submitted successfully.',
            $response['message']
        );

        Http::assertSent(function ($request) {
            return $request->url() === 'https://smsservice.inexphone.ge/api/v1/sms/one'
                && $request->method() === 'POST'
                && $request['phone'] === '995591950549'
                && $request['subject'] === 'Test'
                && $request['message'] === 'Hello from package'
                && $request['ignore_blacklist'] === false;
        });
    }
}