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

namespace App\Repository;

use App\Entity\DeviceToken;
use Carbon\CarbonImmutable;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

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
