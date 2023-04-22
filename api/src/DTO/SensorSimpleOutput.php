<?php

declare(strict_types=1);

namespace App\DTO;

use App\Entity\SensorInterface;
use Ramsey\Uuid\UuidInterface;

class SensorSimpleOutput
{
    public UuidInterface $id;

    public function __construct(UuidInterface $id)
    {
        $this->id = $id;
    }

    public static function create(SensorInterface $device): self
    {
        return new self($device->getId());
    }
}
