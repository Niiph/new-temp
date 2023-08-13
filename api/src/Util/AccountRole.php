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

namespace App\Util;

enum AccountRole: string
{
    case ROLE_ADMIN = 'ROLE_ADMIN';
    case ROLE_USER  = 'ROLE_USER';
}
