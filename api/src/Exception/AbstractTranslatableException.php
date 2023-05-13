<?php
/**
 * This file is part of the *TBD* package.
 *
 * (c) Piotr Opioła <piotr@opiola.eu>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace App\Exception;

use Symfony\Component\HttpKernel\Exception\HttpException;
use Throwable;

abstract class AbstractTranslatableException extends HttpException implements TranslatableExceptionInterface
{
    public function __construct(
        Throwable $previous = null,
        array $headers = [],
        int $code = 0
    ) {
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
