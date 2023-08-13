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

use ApiPlatform\Symfony\Validator\Exception\ValidationException;
use App\Exception\TranslatableExceptionInterface;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Contracts\Translation\TranslatorInterface;

readonly class ExceptionEventListener
{
    public function __construct(
        private TranslatorInterface $translator,
    ) {
    }

    #[AsEventListener(KernelEvents::EXCEPTION)]
    public function onKernelException(ExceptionEvent $event): void
    {
        $throwable = $event->getThrowable();
        if ($throwable instanceof TranslatableExceptionInterface) {
            $message = $this->translator->trans($throwable->getMessageTranslation(), $throwable->getMessageParameters());
        }

        $code = match (true) {
            is_a($throwable, ValidationException::class) => 422,
            is_a($throwable, HttpExceptionInterface::class) => $throwable->getStatusCode(),
            default => 500
        };

        $event->setResponse(new JsonResponse($message ?? $throwable->getMessage(), $code));
    }
}
