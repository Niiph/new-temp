<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\Common\Collections\Collection;

interface SensorInterface extends IdentifiableInterface, CreatedAtInterface
{
    public function getDevice(): DeviceInterface;

    public function setDevice(DeviceInterface $device): void;

    public function getName(): string;

    public function setName(string $name): void;

    public function getPin(): int;

    public function setPin(int $pin): void;

    public function getAddress(): string;

    public function setAddress(string $address): void;

    public function getMinimum(): ?int;

    public function setMinimum(?int $minimum): void;

    public function getMaximum(): ?int;

    public function setMaximum(?int $maximum): void;

    public function isActive(): bool;

    public function setActive(bool $active): void;

    public function getReadings(): Collection;

    public function addReading(ReadingInterface $reading): void;

    public function removeReading(ReadingInterface $reading): void;

    /** @return Collection<SensorSettingsInterface> */
    public function getSensorSettings(): Collection;

    public function addSensorSettings(SensorSettingsInterface $sensorSettings): void;

    public function removeSensorSettings(SensorSettingsInterface $sensorSettings): void;
}
