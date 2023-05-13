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

namespace App\DTO;

use Carbon\CarbonInterface;

class DeviceTokenOutput implements OutputInterface
{
    public function __construct(
        public CarbonInterface $date,
    ) {
    }

    /** @param CarbonInterface $date */
    public static function createOutput(mixed $date): self
    {
        return new self($date);
    }
}
