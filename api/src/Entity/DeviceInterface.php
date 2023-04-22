<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\Common\Collections\Collection;

interface DeviceInterface extends IdentifiableInterface, CreatedAtInterface
{
    public function getName(): string;

    public function isActive(): bool;

    public function getShortId(): string;

    public function getUser(): UserInterface;

    public function getDevicePassword(): ?string;

    public function generateDevicePassword(): string;

    /** @return Collection<SensorInterface> */
    public function getSensors(): Collection;

    public function addSensor(SensorInterface $sensor): void;

    public function removeSensor(SensorInterface $sensor): void;

    /** @return Collection<DeviceTokenInterface */
    public function getDeviceTokens(): Collection;

    public function addDeviceToken(DeviceTokenInterface $deviceToken): void;

    public function removeDeviceToken(DeviceTokenInterface $deviceToken): void;

    public function isTokenValid(string $token): bool;
}
