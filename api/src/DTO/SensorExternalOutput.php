<?php
/*
 * This file is part of the *TBD* package.
 *
 * (c) Piotr Opioła <piotr@opiola.eu>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace App\DTO;

use App\Entity\SensorInterface;

class SensorExternalOutput implements OutputInterface
{
    public function __construct(
        public ?int $pin,
        public ?string $address,
    ) {
    }

    /** @param SensorInterface $data */
    public static function createOutput(mixed $data): self
    {
        return new self(
            $data->getPin(),
            $data->getAddress(),
        );
    }
}
