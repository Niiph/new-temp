<?php

declare(strict_types=1);

namespace App\DTO;

use Carbon\CarbonInterface;

class DeviceTokenOutput
{
    public function __construct(
        public CarbonInterface $date,
    )
    {
    }

    public static function create(CarbonInterface $date): self
    {
        return new self($date);
    }
}
