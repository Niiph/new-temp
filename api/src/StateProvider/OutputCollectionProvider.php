<?php

declare(strict_types=1);

namespace App\StateProvider;

use ApiPlatform\Doctrine\Orm\Paginator;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\Pagination\TraversablePaginator;
use ApiPlatform\State\ProviderInterface;
use App\DTO\OutputInterface;
use App\Exception\InvalidOperationException;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\DependencyInjection\Attribute\Autowire;

readonly class OutputCollectionProvider implements ProviderInterface
{
    public function __construct(
        #[Autowire(service: 'api_platform.doctrine.orm.state.collection_provider')]
        private ProviderInterface $collectionProvider,
    ) {
    }

    public function provide(Operation $operation, array $uriVariables = [], array $context = []): object|array|null
    {
        if (!$operation instanceof GetCollection) {
            throw new InvalidOperationException([GetCollection::class], get_class($operation));
        }

        /** @var Paginator $collection */
        $collection = $this->collectionProvider->provide($operation, $uriVariables, $context);

        if ($operation->getOutput() && is_a($operation->getOutput()['class'], OutputInterface::class, true)) {
            $array = [];
            foreach ($collection as $item) {
                $array[] = $operation->getOutput()['class']::createOutput($item);
            }

            return new TraversablePaginator(
                new ArrayCollection($array),
                $collection->getCurrentPage(),
                $collection->getItemsPerPage(),
                $collection->getTotalItems()
            );
        }

        return $collection;
    }
}
