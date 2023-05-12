<?php

declare(strict_types=1);

namespace App\DTO;

use App\Entity\SensorInterface;
use Ramsey\Uuid\UuidInterface;

class SensorListOutput implements OutputInterface
{
public function __construct(
    public UuidInterface $id,
    public string $name,
) {}

    /** @param SensorInterface $data */
    public static function createOutput(mixed $data): self
    {
        return new self(
            $data->getId(),
            $data->getName(),
        );
    }
}
