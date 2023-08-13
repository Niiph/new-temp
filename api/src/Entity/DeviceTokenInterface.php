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

use Carbon\CarbonInterface;

interface DeviceTokenInterface extends IdentifiableInterface, CreatedAtInterface
{
    public function getDevice(): DeviceInterface;

    public function setDevice(DeviceInterface $device): void;

    public function getExpirationTime(): CarbonInterface;

    public function getToken(): string;

    public function isValid(): bool;
}
