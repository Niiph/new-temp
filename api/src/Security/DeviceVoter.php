<?php

declare(strict_types=1);

namespace App\Security;

use ApiPlatform\Doctrine\Orm\Paginator;
use ApiPlatform\State\Pagination\PaginatorInterface;
use App\Entity\DeviceInterface;
use App\Entity\UserInterface;
use LogicException;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class DeviceVoter extends Voter
{
    public const LIST = 'list_devices';

    protected function supports(string $attribute, mixed $subject): bool
    {
        if (self::LIST === $attribute && $subject instanceof PaginatorInterface) {
            return true;
        }

        return $subject instanceof DeviceInterface;
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();
        if (!$user instanceof UserInterface) {
            return false;
        }

        return match ($attribute) {
            self::LIST => true,
            default => throw new LogicException('Misconfigured voter')
        };
    }
}
