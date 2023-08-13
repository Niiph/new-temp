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

use Throwable;

class UserAlreadyExistsException extends AbstractTranslatableException
{
    public function __construct(
        private readonly string $username,
        Throwable $previous = null,
        array $headers = [],
        int $code = 0
    ) {
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
