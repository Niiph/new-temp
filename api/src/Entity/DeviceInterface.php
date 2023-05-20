<?php
/**
 * This file is part of the *TBD* package.
 *
 * (c) Piotr Opioła <piotr@opiola.eu>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace App\Entity;

use Doctrine\Common\Collections\Collection;

interface DeviceInterface extends IdentifiableInterface, CreatedAtInterface, ActiveInterface
{
    public function getName(): string;

    public function getShortId(): string;

    public function getUser(): UserInterface;

    public function getDevicePassword(): ?string;

    public function generateDevicePassword(): string;

    /** @return Collection<SensorInterface> */
    public function getSensors(): Collection;

    public function addSensor(SensorInterface $sensor): void;

    public function removeSensor(SensorInterface $sensor): void;

    /** @return Collection<DeviceTokenInterface> */
    public function getDeviceTokens(): Collection;

    public function addDeviceToken(DeviceTokenInterface $deviceToken): void;

    public function removeDeviceToken(DeviceTokenInterface $deviceToken): void;

    public function isTokenValid(string $token): bool;
}
