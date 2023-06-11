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

namespace App\Doctrine;

use ApiPlatform\Doctrine\Orm\Extension\QueryCollectionExtensionInterface;
use ApiPlatform\Doctrine\Orm\Util\QueryNameGeneratorInterface;
use ApiPlatform\Metadata\Operation;
use App\Entity\DeviceInterface;
use App\Entity\UserInterface;
use Doctrine\ORM\QueryBuilder;
use Symfony\Bundle\SecurityBundle\Security;

class DevicesListExtension implements QueryCollectionExtensionInterface
{
    private const LIST = 'devices_list';
    private const FULL_LIST = 'devices_full_list';
    private const SIMPLE_LIST = 'devices_simple_list';

    public function __construct(
        private readonly Security $security,
    ) {
    }

    public function applyToCollection(
        QueryBuilder                $queryBuilder,
        QueryNameGeneratorInterface $queryNameGenerator,
        string                      $resourceClass,
        Operation                   $operation = null,
        array                       $context = []
    ): void {
        if (!is_a($resourceClass, DeviceInterface::class, true) || !in_array($operation->getShortName(), $this->shortNames())) {
            return;
        }

        $user = $this->security->getUser();
        if (!$user instanceof UserInterface) {
            return;
        }

        $alias = $queryBuilder->getRootAliases()[0];
        $queryBuilder
            ->andWhere($alias.'.user = :user')
            ->setParameter('user', $user)
            ->orderBy($alias.'.active', 'DESC')
            ->addOrderBy($alias.'.name');

        if (self::LIST === $operation->getShortName()) {
            $queryBuilder->andWhere($alias.'.active = true');
        }
    }

    private function shortNames(): array
    {
        return [
            self::LIST,
            self::FULL_LIST,
            self::SIMPLE_LIST,
        ];
    }
}
