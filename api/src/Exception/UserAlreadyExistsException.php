<?php

declare(strict_types=1);

namespace App\Exception;

use Throwable;

class UserAlreadyExistsException extends TranslatableException
{
    public function __construct(
        private readonly string $username,
        Throwable $previous = null,
        array $headers = [],
        int $code = 0
    )
    {
        parent::__construct($previous, $headers, $code);
    }

    public function getMessageTranslation(): string
    {
        return 'app.exception.user_already_exists';
    }

    public function getMessageParameters(): array
    {
        return ['%username%' => $this->username];
    }
}
