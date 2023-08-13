<?php
/**
 * This file is part of the Diagla package.
 *
 * (c) Piotr Opioła <piotr@opiola.eu>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

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
