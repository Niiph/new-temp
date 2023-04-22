<?php
declare(strict_types=1);

namespace App\Entity;

interface ReadingInterface extends CreatedAtInterface
{
    public function getId(): int;

    public function getValue(): float;

    public function getSensor(): SensorInterface;
}
