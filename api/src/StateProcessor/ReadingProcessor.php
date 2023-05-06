<?php

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
        private EntityManagerInterface  $entityManager,
        private DeviceResolverInterface $deviceResolver,
    ) {
    }

    /** @param ReadingInput $data */
    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = []): void
    {
        if (empty($data->value) || !is_numeric($data->value)) {
            return;
        }

        $device = $this->deviceResolver->resolveDevice();
        $sensor = $device?->getSensors()->filter(static function (SensorInterface $sensor) use ($uriVariables) {
            return $sensor->getId()->equals($uriVariables['id']);
            })->first();

        if (!$sensor instanceof SensorInterface) {
            return;
        }

        $reading = new Reading($data->value, $sensor);
        $this->entityManager->persist($reading);
        $this->entityManager->flush();
    }
}
