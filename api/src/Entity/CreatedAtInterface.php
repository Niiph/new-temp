<?php

declare(strict_types=1);

namespace App\Entity;

use Carbon\CarbonInterface;

interface CreatedAtInterface
{
    public function getCreatedAt(): CarbonInterface;
}
