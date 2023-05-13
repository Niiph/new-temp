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

namespace App\Util;

class StringLengthUtil
{
    public const MIN          = 1;
    public const MAX          = 255;
    public const PASSWORD_MIN = 6;
    public const PASSWORD_MAX = 64;
    public const TYPE_NAME    = 8;

    private function __construct()
    {
    }
}
