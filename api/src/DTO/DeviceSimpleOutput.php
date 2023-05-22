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

use App\Entity\DeviceInterface;
use App\Entity\SensorInterface;
use Ramsey\Uuid\UuidInterface;

class DeviceSimpleOutput implements OutputInterface
{
    public function __construct(
        public UuidInterface $id,
        public string $name,
    ) {
    }

    /** @param DeviceInterface $data */
    public static function createOutput(mixed $data): self
    {
        return new self(
            $data->getId(),
            $data->getName(),
        );
    }
}
