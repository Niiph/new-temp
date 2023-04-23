<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\DeviceInterface;
use App\Entity\DeviceTokenInterface;
use App\Repository\DeviceTokenRepositoryInterface;
use Symfony\Component\HttpFoundation\RequestStack;

readonly class DeviceResolver implements DeviceResolverInterface
{
    private const TOKEN = 'x-authentication-token';

    public function __construct(
        private RequestStack $requestStack,
        private DeviceTokenRepositoryInterface $deviceTokenRepository,
    ) {
    }

    public function resolveDevice(): ?DeviceInterface
    {
        $token = trim($this->requestStack->getCurrentRequest()->headers->get(self::TOKEN));

        if (!$token) {
            return null;
        }

        /** @var DeviceTokenInterface $token */
        $token = $this->deviceTokenRepository->findOneBy(['token' => $token]);
        dump($token);
        if (!$token || !$token->isValid()) {
            return null;
        }

        return $token->getDevice();
    }
}
