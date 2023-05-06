<?php

declare(strict_types=1);

namespace App\Exception;

use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;

interface TranslatableExceptionInterface extends HttpExceptionInterface
{
    public function getMessageParameters(): array;

    public function getMessageTranslation(): string;
}
