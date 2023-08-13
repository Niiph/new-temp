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

namespace App\StateProcessor;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\DTO\ReadingInput;
use App\Entity\Reading;
use App\Entity\SensorInterface;
use App\Service\DeviceResolverInterface;
use Doctrine\ORM\EntityManagerInterface;

readonly class ReadingProcessor implements ProcessorInterface
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private DeviceResolverInterface $deviceResolver,
    ) {
    }

    /** @param ReadingInput $data */
    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = []): void
    {
        if (empty($data->value) || !is_numeric($data->value)) {
            return;
        }

        $sensor = $this->deviceResolver->resolveSensor($uriVariables);

        if (!$sensor instanceof SensorInterface) {
            return;
        }

        $reading = new Reading(
            $data->value,
            $data->type,
            $sensor
        );
        $this->entityManager->persist($reading);
        $this->entityManager->flush();
    }
}
