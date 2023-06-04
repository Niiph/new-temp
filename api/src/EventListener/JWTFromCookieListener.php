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

namespace App\EventListener;

use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class JWTFromCookieListener
{
    #[AsEventListener(KernelEvents::REQUEST, priority: 10)]
    public function onKernelRequest(RequestEvent $event): void
    {
        $request = $event->getRequest();

        if ($request->cookies->has('token')) {
            $token = $request->cookies->get('token');
            $request->headers->set('Authorization', 'Bearer '.$token);
        }
    }
}
