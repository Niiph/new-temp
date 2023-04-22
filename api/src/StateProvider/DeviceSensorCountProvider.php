<?php

declare(strict_types=1);

namespace App\StateProvider;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProviderInterface;
use App\DTO\DeviceSensorsCountOutput;
use App\Repository\DeviceRepositoryInterface;

readonly class DeviceSensorCountProvider implements ProviderInterface
{
    public function __construct(private DeviceRepositoryInterface $deviceRepository)
    {
    }

    public function provide(Operation $operation, array $uriVariables = [], array $context = []): object|array|null
    {
        $device = $this->deviceRepository->find($uriVariables['id']);

        return DeviceSensorsCountOutput::create($device);
    }
}
