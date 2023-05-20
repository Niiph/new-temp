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
use App\DTO\DeviceCreateInput;
use App\Entity\Device;
use App\Entity\DeviceInterface;
use App\Entity\UserInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;

readonly class DeviceCreateProcessor implements ProcessorInterface
{
    public function __construct(
        private Security               $security,
        private EntityManagerInterface $entityManager,
    ) {
    }

    /** @param DeviceCreateInput $data */
    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = []): DeviceInterface
    {
        /** @var UserInterface $user */
        $user = $this->security->getUser();

        $device = new Device(
            $user,
            $data->name,
        );

        $this->entityManager->persist($device);
        $this->entityManager->flush();

        return $device;
    }
}
