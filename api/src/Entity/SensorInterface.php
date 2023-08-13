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

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\Selectable;

interface SensorInterface extends IdentifiableInterface, CreatedAtInterface, ActiveInterface, OwnerInterface, NameInterface
{
    public function getDevice(): DeviceInterface;

    public function setDevice(DeviceInterface $device): void;

    public function getPin(): ?int;

    public function setPin(?int $pin): void;

    public function getAddress(): ?string;

    public function setAddress(?string $address): void;

    public function getMinimum(): ?int;

    public function setMinimum(?int $minimum): void;

    public function getMaximum(): ?int;

    public function setMaximum(?int $maximum): void;

    public function getReadings(): Collection&Selectable;

    public function addReading(ReadingInterface $reading): void;

    public function removeReading(ReadingInterface $reading): void;

    /** @return Collection<SensorSettingsInterface> */
    public function getSensorSettings(): Collection;

    public function addSensorSettings(SensorSettingsInterface $sensorSettings): void;

    public function removeSensorSettings(SensorSettingsInterface $sensorSettings): void;
}
