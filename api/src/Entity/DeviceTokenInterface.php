<?php

declare(strict_types=1);

namespace App\Entity;

use Carbon\CarbonInterface;

interface DeviceTokenInterface extends IdentifiableInterface, CreatedAtInterface
{
    public function getDevice(): DeviceInterface;

    public function setDevice(DeviceInterface $device): void;

    public function getExpirationTime(): CarbonInterface;

    public function getToken(): string;

    public function isValid(): bool;
}
