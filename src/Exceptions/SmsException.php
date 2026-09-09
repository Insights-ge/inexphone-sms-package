<?php

declare(strict_types=1);

namespace Inexphone\Sms\Exceptions;

use RuntimeException;

class SmsException extends RuntimeException
{
    /**
     * @param array<string, list<string>>|null $errors
     */
    public function __construct(
        string $message,
        public readonly int $status,
        public readonly ?array $errors = null,
    ) {
        parent::__construct($message);
    }
}