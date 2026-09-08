<?php

namespace Inexphone\Sms\Exceptions;

use RuntimeException;

class SmsException extends RuntimeException
{
    public function __construct(
        string $message,
        public readonly int $status,
        public readonly ?array $errors = null,
    ) {
        parent::__construct($message);
    }
}