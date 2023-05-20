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
use App\DTO\DeviceOutput;
use App\Entity\ActiveInterface;
use App\Entity\DeviceInterface;
use Doctrine\ORM\EntityManagerInterface;
use InvalidArgumentException;

readonly class SensorChangeNameProcessor implements ProcessorInterface
{
    public function __construct(
        private EntityManagerInterface $entityManager,
    ) {
    }

    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = []): DeviceOutput
    {
        /** @var DeviceInterface $object */
        $object = $context['previous_data'];
        if (!is_object($object) || !method_exists($object, 'getId')) {
            throw new InvalidArgumentException();
        }

        $entity = $this->entityManager->getReference(get_class($object), $object->getId());

        /** @var DeviceInterface $entity */
        $entity->setName($data->name);
        $this->entityManager->flush();

        return DeviceOutput::createOutput($entity);
    }
}
