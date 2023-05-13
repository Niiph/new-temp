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

namespace App\StateProcessor;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\DTO\DeviceTokenInput;
use App\DTO\DeviceTokenOutput;
use App\Entity\DeviceInterface;
use App\Entity\DeviceToken;
use App\Repository\DeviceRepositoryInterface;
use Carbon\CarbonImmutable;
use Doctrine\ORM\EntityManagerInterface;

readonly class DeviceTokenProcessor implements ProcessorInterface
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private DeviceRepositoryInterface $deviceRepository,
        private string $deviceTokenExpiration,
    ) {
    }

    /** @param DeviceTokenInput $data */
    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = []): DeviceTokenOutput
    {
        if (empty($data->shortId) || !is_string($data->shortId)) {
            return DeviceTokenOutput::createOutput(CarbonImmutable::now());
        }

        $device = $this->deviceRepository->findOneBy(['shortId' => $data->shortId]);
        if (!$device instanceof DeviceInterface) {
            return DeviceTokenOutput::createOutput(CarbonImmutable::now());
        }

        $deviceToken = new DeviceToken($device, $this->deviceTokenExpiration);
        $device->addDeviceToken($deviceToken);
        $this->entityManager->flush();

        return DeviceTokenOutput::createOutput($deviceToken->getCreatedAt());
    }
}
