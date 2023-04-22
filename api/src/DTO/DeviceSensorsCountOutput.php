<?php

declare(strict_types=1);

namespace App\DTO;

use App\Entity\DeviceInterface;

class DeviceSensorsCountOutput
{
    public int $count;

    public function __construct(int $count)
    {
        $this->count = $count;
    }

    public static function create(DeviceInterface $device): self
    {
        return new self($device->getSensors()->count());
    }
}
