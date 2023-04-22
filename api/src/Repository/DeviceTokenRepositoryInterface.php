<?php

declare(strict_types=1);

namespace App\Repository;

use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ObjectRepository;

interface DeviceTokenRepositoryInterface extends ObjectRepository
{
    public function getExpiredDeviceTokens(): QueryBuilder;
}
