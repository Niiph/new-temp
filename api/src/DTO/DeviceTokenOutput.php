<?php

declare(strict_types=1);

namespace App\DTO;

use Carbon\CarbonInterface;

class DeviceTokenOutput implements OutputInterface
{
    public function __construct(
        public CarbonInterface $date,
    )
    {
    }

    /** @param CarbonInterface $date */
    public static function createOutput(mixed $date): self
    {
        return new self($date);
    }
}
