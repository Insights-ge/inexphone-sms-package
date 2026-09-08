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

    public function test_it_sends_a_commercial_sms(): void
    {
        Http::fake([
            'https://smsservice.inexphone.ge/api/v1/sms/commercial' => Http::response([
                'message' => 'Commercial SMS submitted successfully.',
                'data' => [
                    'id' => 'commercial-test-uuid',
                ],
            ], 201),
        ]);

        $response = Sms::sendCommercial(
            phone: '995591950549',
            subject: 'Commercial Test',
            message: 'Commercial message',
        );

        $this->assertSame(
            'Commercial SMS submitted successfully.',
            $response['message']
        );

        Http::assertSent(function ($request) {
            return $request->url() === 'https://smsservice.inexphone.ge/api/v1/sms/commercial'
                && $request->method() === 'POST'
                && $request['phone'] === '995591950549'
                && $request['subject'] === 'Commercial Test'
                && $request['message'] === 'Commercial message';
        });
    }

    public function test_it_sends_bulk_sms(): void
    {
        Http::fake([
            'https://smsservice.inexphone.ge/api/v1/sms/bulk' => Http::response([
                'message' => 'Bulk SMS submitted successfully.',
                'data' => [
                    'id' => 'bulk-test-uuid',
                ],
            ], 201),
        ]);

        $response = Sms::sendBulk(
            subject: 'Bulk Test',
            message: 'Bulk message',
            phoneNumbers: [
                '995591111111',
                '995592222222',
            ],
        );

        $this->assertSame(
            'Bulk SMS submitted successfully.',
            $response['message']
        );

        Http::assertSent(function ($request) {
            return $request->url() === 'https://smsservice.inexphone.ge/api/v1/sms/bulk'
                && $request->method() === 'POST'
                && $request['subject'] === 'Bulk Test'
                && $request['message'] === 'Bulk message'
                && $request['phone_numbers'] === [
                    '995591111111',
                    '995592222222',
                ];
        });
    }

    public function test_it_lists_sms_messages(): void
    {
        Http::fake([
            'https://smsservice.inexphone.ge/api/v1/sms*' => Http::response([
                'message' => 'SMS list retrieved successfully.',
                'data' => [
                    [
                        'id' => 'test-uuid',
                        'attributes' => [
                            'number' => '995591111111',
                            'subject' => 'Test',
                            'message' => 'Hello',
                        ],
                    ],
                ],
                'meta' => [
                    'pagination' => [
                        'currentPage' => 1,
                        'perPage' => 15,
                        'total' => 1,
                    ],
                ],
            ], 200),
        ]);

        $response = Sms::list([
            'page' => 1,
            'perPage' => 15,
            'sort' => '-createDate',
        ]);

        $this->assertSame(
            'SMS list retrieved successfully.',
            $response['message']
        );

        Http::assertSent(function ($request) {
            return $request->url() === 'https://smsservice.inexphone.ge/api/v1/sms?page=1&perPage=15&sort=-createDate'
                && $request->method() === 'GET';
        });
    }

    public function test_it_finds_an_sms(): void
    {
        Http::fake([
            'https://smsservice.inexphone.ge/api/v1/sms/test-uuid' => Http::response([
                'message' => 'SMS retrieved successfully.',
                'data' => [
                    'id' => 'test-uuid',
                    'attributes' => [
                        'number' => '995591111111',
                        'subject' => 'Test',
                        'message' => 'Hello',
                    ],
                ],
            ], 200),
        ]);

        $response = Sms::find('test-uuid');

        $this->assertSame(
            'SMS retrieved successfully.',
            $response['message']
        );

        Http::assertSent(function ($request) {
            return $request->url() === 'https://smsservice.inexphone.ge/api/v1/sms/test-uuid'
                && $request->method() === 'GET';
        });
    }

    public function test_it_throws_sms_exception_when_sending_fails(): void
    {
        Http::fake([
            'https://smsservice.inexphone.ge/api/v1/sms/one' => Http::response([
                'message' => 'Invalid phone number.',
                'errors' => [
                    'phone' => ['The phone number is invalid.'],
                ],
            ], 422),
        ]);

        $this->expectException(\Inexphone\Sms\Exceptions\SmsException::class);
        $this->expectExceptionMessage('Invalid phone number.');

        Sms::send(
            phone: 'invalid',
            subject: 'Test',
            message: 'Hello',
        );
    }

    public function test_it_includes_status_and_errors_in_sms_exception(): void
    {
        Http::fake([
            'https://smsservice.inexphone.ge/api/v1/sms/one' => Http::response([
                'message' => 'Invalid phone number.',
                'errors' => [
                    'phone' => ['The phone number is invalid.'],
                ],
            ], 422),
        ]);

        try {
            Sms::send(
                phone: 'invalid',
                subject: 'Test',
                message: 'Hello',
            );

            $this->fail('SmsException was not thrown.');
        } catch (\Inexphone\Sms\Exceptions\SmsException $exception) {
            $this->assertSame(422, $exception->status);
            $this->assertSame(
                ['phone' => ['The phone number is invalid.']],
                $exception->errors
            );
        }
    }

    public function test_it_sends_callback_urls_with_single_sms(): void
    {
        Http::fake([
            'https://smsservice.inexphone.ge/api/v1/sms/one' => Http::response([
                'message' => 'SMS submitted successfully.',
                'data' => [
                    'id' => 'callback-test-uuid',
                ],
            ], 201),
        ]);

        Sms::send(
            phone: '995591111111',
            subject: 'Callback Test',
            message: 'Hello',
            ignoreBlacklist: true,
            submitCallbackUrl: 'https://example.com/submit',
            deliveryCallbackUrl: 'https://example.com/delivery',
        );

        Http::assertSent(function ($request) {
            return $request['ignore_blacklist'] === true
                && $request['submit_callback_url'] === 'https://example.com/submit'
                && $request['delivery_callback_url'] === 'https://example.com/delivery';
        });
    }

    public function test_it_sends_callback_urls_with_bulk_sms(): void
    {
        Http::fake([
            'https://smsservice.inexphone.ge/api/v1/sms/bulk' => Http::response([
                'message' => 'Bulk SMS submitted successfully.',
                'data' => [
                    'id' => 'bulk-callback-test-uuid',
                ],
            ], 201),
        ]);

        Sms::sendBulk(
            subject: 'Bulk Callback Test',
            message: 'Hello',
            phoneNumbers: [
                '995591111111',
                '995592222222',
            ],
            submitCallbackUrl: 'https://example.com/submit',
            deliveryCallbackUrl: 'https://example.com/delivery',
        );

        Http::assertSent(function ($request) {
            return $request['submit_callback_url'] === 'https://example.com/submit'
                && $request['delivery_callback_url'] === 'https://example.com/delivery';
        });
    }

    public function test_it_does_not_send_callback_urls_when_they_are_not_provided(): void
    {
        Http::fake([
            'https://smsservice.inexphone.ge/api/v1/sms/one' => Http::response([
                'message' => 'SMS submitted successfully.',
                'data' => [
                    'id' => 'no-callback-test-uuid',
                ],
            ], 201),
        ]);

        Sms::send(
            phone: '995591111111',
            subject: 'No Callback Test',
            message: 'Hello',
        );

        Http::assertSent(function ($request) {
            return ! $request->hasHeader('submit_callback_url')
                && ! $request->hasHeader('delivery_callback_url')
                && ! array_key_exists('submit_callback_url', $request->data())
                && ! array_key_exists('delivery_callback_url', $request->data());
        });
    }

    public function test_it_does_not_send_callback_urls_with_bulk_sms_when_they_are_not_provided(): void
    {
        Http::fake([
            'https://smsservice.inexphone.ge/api/v1/sms/bulk' => Http::response([
                'message' => 'Bulk SMS submitted successfully.',
                'data' => [
                    'id' => 'bulk-no-callback-test-uuid',
                ],
            ], 201),
        ]);

        Sms::sendBulk(
            subject: 'Bulk No Callback Test',
            message: 'Hello',
            phoneNumbers: [
                '995591111111',
                '995592222222',
            ],
        );

        Http::assertSent(function ($request) {
            return ! array_key_exists('submit_callback_url', $request->data())
                && ! array_key_exists('delivery_callback_url', $request->data());
        });
    }

    public function test_it_throws_sms_exception_when_finding_sms_fails(): void
    {
        Http::fake([
            'https://smsservice.inexphone.ge/api/v1/sms/invalid-uuid' => Http::response([
                'message' => 'SMS with provided uuid not found.',
            ], 404),
        ]);

        try {
            Sms::find('invalid-uuid');

            $this->fail('SmsException was not thrown.');
        } catch (\Inexphone\Sms\Exceptions\SmsException $exception) {
            $this->assertSame(404, $exception->status);
            $this->assertSame(
                'SMS with provided uuid not found.',
                $exception->getMessage()
            );
        }
    }

    public function test_it_lists_sms_messages_with_filters(): void
    {
        Http::fake([
            'https://smsservice.inexphone.ge/api/v1/sms*' => Http::response([
                'message' => 'SMS list retrieved successfully.',
                'data' => [],
                'meta' => [
                    'pagination' => [
                        'currentPage' => 2,
                        'perPage' => 10,
                        'total' => 0,
                    ],
                ],
            ], 200),
        ]);

        Sms::list([
            'page' => 2,
            'perPage' => 10,
            'sort' => '-createDate',
            'filters' => [
                'subject' => 'Test',
                'type' => 'transactional',
                'state' => 'delivered',
                'number' => '995591111111',
                'dateStart' => '01/09/2026',
                'dateEnd' => '08/09/2026',
            ],
        ]);

        Http::assertSent(function ($request) {
            return $request->url() === 'https://smsservice.inexphone.ge/api/v1/sms?page=2&perPage=10&sort=-createDate&filters%5Bsubject%5D=Test&filters%5Btype%5D=transactional&filters%5Bstate%5D=delivered&filters%5Bnumber%5D=995591111111&filters%5BdateStart%5D=01%2F09%2F2026&filters%5BdateEnd%5D=08%2F09%2F2026'
                && $request->method() === 'GET';
        });
    }

    public function test_it_sends_required_api_headers(): void
    {
        Http::fake([
            'https://smsservice.inexphone.ge/api/v1/sms/one' => Http::response([
                'message' => 'SMS submitted successfully.',
                'data' => [
                    'id' => 'headers-test-uuid',
                ],
            ], 201),
        ]);

        Sms::send(
            phone: '995591111111',
            subject: 'Headers Test',
            message: 'Hello',
        );

        Http::assertSent(function ($request) {
            return $request->hasHeader('Authorization', 'Bearer test-token')
                && $request->hasHeader('Accept', 'application/json')
                && $request->hasHeader('Accept-Language', 'ka');
        });
    }

}