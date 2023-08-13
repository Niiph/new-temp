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

interface SensorSettingsInterface
{
    public function getMinimum(): ?int;

    public function setMinimum(?int $minimum): void;

    public function getMaximum(): ?int;

    public function setMaximum(?int $maximum): void;

    public function getType(): string;

    public function setType(string $type): void;

    public function getSensor(): SensorInterface;

    public function setSensor(SensorInterface $sensor): void;
}
