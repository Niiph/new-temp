<?php

declare(strict_types=1);

namespace App\DTO;

use App\Entity\DeviceInterface;
use App\Entity\SensorInterface;
use Ramsey\Uuid\UuidInterface;

class DeviceListOutput implements OutputInterface
{
public function __construct(
    public UuidInterface $id,
    public string $name,
    /** @var SensorListOutput[] $sensors */
    public array $sensors,
) {}

    /** @param DeviceInterface $data */
    public static function createOutput(mixed $data): self
    {
        $sensors = $data->getSensors()->map(static function (SensorInterface $sensor) {
            return SensorListOutput::createOutput($sensor);
        })->toArray();

       return new self(
           $data->getId(),
           $data->getName(),
           $sensors,
       );
    }
}
