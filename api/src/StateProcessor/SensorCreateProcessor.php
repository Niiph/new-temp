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
use App\DTO\SensorCreateInput;
use App\Entity\Device;
use App\Entity\DeviceInterface;
use App\Entity\Sensor;
use App\Entity\SensorInterface;
use App\Entity\UserInterface;
use App\Repository\DeviceRepositoryInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;

readonly class SensorCreateProcessor implements ProcessorInterface
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private DeviceRepositoryInterface $deviceRepository,
    ) {
    }

    /** @param SensorCreateInput $data */
    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = []): SensorInterface
    {
        /** @var DeviceInterface $device */
        $device = $this->deviceRepository->find($data->deviceId);

        $sensor = new Sensor(
            $device,
            $data->name,
        );

        $this->entityManager->persist($sensor);
        $this->entityManager->flush();

        return $sensor;
    }
}
