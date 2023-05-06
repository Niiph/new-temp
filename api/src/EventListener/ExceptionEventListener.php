<?php

declare(strict_types=1);

namespace App\EventListener;

use App\Exception\TranslatableExceptionInterface;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Contracts\Translation\TranslatorInterface;

class ExceptionEventListener
{
    public function __construct(
        private readonly TranslatorInterface $translator,
    ) {
    }

    #[AsEventListener(KernelEvents::EXCEPTION)]
    public function onKernelException(ExceptionEvent $event): void
    {
        $throwable = $event->getThrowable();
        if (!$throwable instanceof TranslatableExceptionInterface) {
            return;
        }

        $message = $this->translator->trans($throwable->getMessageTranslation(), $throwable->getMessageParameters());

        $event->setResponse(new JsonResponse($message, $throwable->getStatusCode()));
    }
}
