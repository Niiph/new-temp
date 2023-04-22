<?php

declare(strict_types=1);

namespace App\StateProcessor;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\DTO\ReadingInput;
use App\Entity\Reading;
use App\Entity\SensorInterface;
use App\Repository\SensorRepositoryInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\DependencyInjection\Attribute\Autowire;

readonly class ReadingProcessor implements ProcessorInterface
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private SensorRepositoryInterface $sensorRepository,
    )
    {
    }

    /** @param ReadingInput $data */
    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = []): void
    {
        if (empty($data->value)
            || !is_numeric($data->value)
            || empty($data->token)
            || !is_string($data->token)) {
            return;
        }
        $sensor = $this->sensorRepository->find($uriVariables['id']);
        if (!$sensor instanceof SensorInterface) {
            return;
        }

        if (!$sensor->getDevice()->isTokenValid($data->token)) {
            return;
        }

        $reading = new Reading($data->value, $sensor);
        $this->entityManager->persist($reading);
        $this->entityManager->flush();
    }
}
