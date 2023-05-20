<?php
/*
 * This file is part of the *TBD* package.
 *
 * (c) Piotr Opioła <piotr@opiola.eu>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace App\StateProcessor;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\DTO\ChangeActiveInput;
use App\Entity\ActiveInterface;
use Doctrine\ORM\EntityManagerInterface;
use InvalidArgumentException;

readonly class SensorChangeActiveProcessor implements ProcessorInterface
{
    public function __construct(
        private EntityManagerInterface $entityManager,
    ) {
    }

    /** @param ChangeActiveInput $data */
    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = []): ActiveInterface
    {
        /** @var ActiveInterface $object */
        $object = $context['previous_data'];
        if (!is_object($object) || !method_exists($object, 'getId')) {
            throw new InvalidArgumentException();
        }

        /* @phpstan-ignore-next-line */
        $entity = $this->entityManager->getReference(get_class($object), $object->getId());

        /** @var ActiveInterface $entity */
        $entity->setActive($data->active);

        $this->entityManager->flush();

        return $entity;
    }
}
