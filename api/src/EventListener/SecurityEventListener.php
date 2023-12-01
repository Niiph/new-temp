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

namespace App\EventListener;

use ApiPlatform\Metadata\Get;
use ApiPlatform\State\ProviderInterface;
use ApiPlatform\Symfony\Security\ResourceAccessCheckerInterface;
use App\DTO\OutputInterface;
use ReflectionProperty;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

readonly class SecurityEventListener
{
    public function __construct(
        #[Autowire(service: 'api_platform.doctrine.orm.state.item_provider')]
        private ProviderInterface              $itemProvider,
        private ResourceAccessCheckerInterface $resourceAccessChecker,
    ) {
    }

    #[AsEventListener(KernelEvents::REQUEST, priority: 3)]
    public function onKernelView(RequestEvent $event): void
    {
        $attributes = $event->getRequest()->attributes;
        $data = $attributes->get('data');
        $operation = $attributes->get('_api_operation');
        $apiNormalizationContext = $attributes->get('_api_normalization_context');
        if (!$data instanceof OutputInterface || !$operation instanceof Get || !$apiNormalizationContext || !$operation->getSecurity()) {
            return;
        }

        $uriVariables = $apiNormalizationContext['uri_variables'] ?? null;

        $entity = $this->itemProvider->provide($operation, $uriVariables);

        if (!$this->resourceAccessChecker->isGranted($apiNormalizationContext['resource_class'], $operation->getSecurity(), ['object' => $entity])) {
            throw new AccessDeniedException('Access Denied.');
        }

        $reflection = new ReflectionProperty(Get::class, 'security');
        $reflection->setValue($operation, null);
    }
}
