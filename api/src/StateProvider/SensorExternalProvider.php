<?php
/**
 * This file is part of the Diagla package.
 *
 * (c) Piotr Opioła <piotr@opiola.eu>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace App\StateProvider;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProviderInterface;
use App\DTO\SensorExternalOutput;
use App\DTO\SensorSimpleOutput;
use App\Entity\SensorInterface;
use App\Service\DeviceResolverInterface;

readonly class SensorExternalProvider implements ProviderInterface
{
    public function __construct(
        private DeviceResolverInterface $deviceResolver,
    ) {
    }

    public function provide(Operation $operation, array $uriVariables = [], array $context = []): object|array|null
    {
        $sensor = $this->deviceResolver->resolveSensor($uriVariables);

        if (!$sensor instanceof SensorInterface) {
            return null;
        }

        return SensorExternalOutput::createOutput($sensor);
    }
}
