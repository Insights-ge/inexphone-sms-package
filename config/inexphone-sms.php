<?php

return [

    'base_url' => env(
        'INEXPHONE_SMS_BASE_URL',
        'https://smsservice.inexphone.ge/api/v1'
    ),

    'token' => env('INEXPHONE_SMS_TOKEN'),

    'language' => env('INEXPHONE_SMS_LANGUAGE', 'ka'),

    'timeout' => (int) env('INEXPHONE_SMS_TIMEOUT', 30),

];