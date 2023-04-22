<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\DeviceInterface;

interface DeviceResolverInterface
{
    public function resolveDevice(): ?DeviceInterface;
}
