<?php

declare(strict_types=1);

namespace App\StateProvider;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProviderInterface;
use App\DTO\SensorSimpleOutput;
use App\Entity\SensorInterface;
use App\Service\DeviceResolverInterface;

readonly class SensorsListProvider implements ProviderInterface
{
    public function __construct(
        private DeviceResolverInterface $deviceResolver,
    )
    {
    }

    public function provide(Operation $operation, array $uriVariables = [], array $context = []): object|array|null
    {
        $device = $this->deviceResolver->resolveDevice();

        if ($device->getShortId() !== $context['uri_variables']['id']) {
            return null;
        }

        return $device->getSensors()->map(static function (SensorInterface $sensor) {
            return SensorSimpleOutput::create($sensor);
        });
    }
}
