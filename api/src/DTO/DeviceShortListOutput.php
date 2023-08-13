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

use App\Entity\DeviceInterface;
use App\Entity\SensorInterface;
use Ramsey\Uuid\UuidInterface;

class DeviceShortListOutput implements OutputInterface
{
    public function __construct(
        public UuidInterface $id,
        public string $name,
        /** @var SensorListOutput[] $sensors */
        public array $sensors,
    ) {
    }

    /** @param DeviceInterface $data */
    public static function createOutput(mixed $data): self
    {
        $sensors = $data->getSensors()->reduce(static function (array $result, SensorInterface $sensor) {
            if ($sensor->isActive()) {
                $result[] = SensorListOutput::createOutput($sensor);
            }

            return $result;
        }, []);

        return new self(
            $data->getId(),
            $data->getName(),
            $sensors,
        );
    }
}
