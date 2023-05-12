<?php

declare(strict_types=1);

namespace App\Exception;

use LogicException;
use Throwable;

class InvalidOperationException extends LogicException
{
    public function __construct(
        private readonly array $supported,
        private readonly string $provided,
        ?Throwable $previous = null
    ) {
        parent::__construct(
            sprintf('This Provider only supports operations of type %s, %s given.', implode(', ', $this->supported), $this->provided),
            0,
            $previous
        );
    }
}
