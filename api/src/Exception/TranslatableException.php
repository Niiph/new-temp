<?php

declare(strict_types=1);

namespace App\Exception;

use Symfony\Component\HttpKernel\Exception\HttpException;
use Throwable;

abstract class TranslatableException extends HttpException implements TranslatableExceptionInterface
{
    public function __construct(
        Throwable $previous = null,
        array $headers = [],
        int $code = 0
    )
    {
        parent::__construct($this->getStatusCode(), $this->getMessageTranslation(), $previous, $headers, $code);
    }

    public function getStatusCode(): int
    {
        return 422;
    }

    public function getMessageParameters(): array
    {
        return [];
    }

    abstract public function getMessageTranslation(): string;
}
