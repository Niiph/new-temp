<?php

declare(strict_types=1);

namespace App\Util;

use Carbon\CarbonInterface;
use Doctrine\ORM\Mapping\Column;

trait CreatedAtTrait
{
    #[Column(type: 'carbon_immutable')]
    private CarbonInterface $createdAt;

    public function getCreatedAt(): CarbonInterface
    {
        return $this->createdAt;
    }
}
