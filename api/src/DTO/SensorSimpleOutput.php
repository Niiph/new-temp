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

namespace App\DTO;

use App\Entity\SensorInterface;
use Ramsey\Uuid\UuidInterface;

class SensorSimpleOutput implements OutputInterface
{
    public UuidInterface $id;

    public function __construct(UuidInterface $id)
    {
        $this->id = $id;
    }

    /** @param SensorInterface $device */
    public static function createOutput(mixed $device): self
    {
        return new self($device->getId());
    }
}
