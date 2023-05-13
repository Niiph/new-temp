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

interface ReadingInterface extends CreatedAtInterface
{
    public function getId(): int;

    public function getValue(): float;

    public function getType(): string;

    public function getSensor(): SensorInterface;
}
