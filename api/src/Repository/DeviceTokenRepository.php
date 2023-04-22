<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Device;
use App\Entity\DeviceToken;
use Carbon\CarbonImmutable;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Persistence\ObjectRepository;

class DeviceTokenRepository extends ServiceEntityRepository implements DeviceTokenRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DeviceToken::class);
    }

    public function getExpiredDeviceTokens(): QueryBuilder
    {
        $queryBuilder = $this->createQueryBuilder('d');
        $queryBuilder
            ->where('d.expirationTime < :now')
            ->setParameter('now', CarbonImmutable::now());

        return $queryBuilder;
    }
}
