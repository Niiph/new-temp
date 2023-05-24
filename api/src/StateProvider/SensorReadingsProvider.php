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

namespace App\StateProvider;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProviderInterface;
use App\DTO\ChartValueOutput;
use App\DTO\SensorReadingsOutput;
use App\Entity\ReadingInterface;
use App\Entity\SensorInterface;
use App\Repository\SensorRepositoryInterface;
use Carbon\CarbonImmutable;
use Doctrine\Common\Collections\Criteria;
use Doctrine\Common\Collections\Expr\Comparison;

readonly class SensorReadingsProvider implements ProviderInterface
{
    public function __construct(
        private SensorRepositoryInterface $sensorRepository,
    ) {
    }

    public function provide(Operation $operation, array $uriVariables = [], array $context = []): object|array|null
    {
        /** @var SensorInterface $sensor */
        $sensor = $this->sensorRepository->find($uriVariables['id']);
        /** @retrun  $readings */
        $readings = $sensor->getReadings()->matching(
            Criteria::create()->andWhere(
                new Comparison('createdAt', '>', CarbonImmutable::parse('-5 days'))
            )
        );

        $array = [];
        /** @var ReadingInterface $reading */
        foreach ($readings as $reading) {
            $array[$reading->getType()][] = ChartValueOutput::createOutput(
                ['value' => $reading->getValue(), 'date' => $reading->getCreatedAt()]
            );
        }

        $output = [];
        foreach ($array as $key => $item) {
            $output[] = SensorReadingsOutput::createOutput($key, $item);
        }

        return $output;
    }
}
