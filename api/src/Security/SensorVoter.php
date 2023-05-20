<?php
/**
 * This file is part of the *TBD* package.
 *
 * (c) Piotr Opioła <piotr@opiola.eu>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace App\Security;

use ApiPlatform\State\Pagination\PaginatorInterface;
use App\Entity\DeviceInterface;
use App\Entity\SensorInterface;
use App\Entity\UserInterface;
use LogicException;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class SensorVoter extends Voter
{
    public const VIEW = 'sensor_get';

    protected function supports(string $attribute, mixed $subject): bool
    {
        if (!in_array($attribute, [
            self::VIEW,
        ])) {
            return true;
        }

        return $subject instanceof SensorInterface;
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();
        if (!$user instanceof UserInterface) {
            return false;
        }

        return match ($attribute) {
            self::VIEW => $this->canView($user, $subject),
            default => throw new LogicException('Misconfigured voter')
        };
    }

    private function canView(UserInterface $user, SensorInterface $sensor): bool
    {
        return $sensor->getDevice()->getUser() === $user;
    }
}
