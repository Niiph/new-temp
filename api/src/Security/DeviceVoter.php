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
use App\Entity\UserInterface;
use LogicException;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class DeviceVoter extends Voter
{
    public const LIST = 'list_devices';
    public const VIEW = 'device_get';
    public const CHANGE_ACTIVE = 'device_change_active';
    public const CREATE = 'device_create';
    public const CHANGE_PASSWORD = 'device_change_password';
    public const CHANGE_NAME = 'device_change_name';

    protected function supports(string $attribute, mixed $subject): bool
    {
        if (self::CREATE === $attribute && null === $subject) {
            return true;
        }

        if (self::LIST === $attribute && $subject instanceof PaginatorInterface) {
            return true;
        }

        if (!in_array($attribute, [
            self::VIEW,
            self::CHANGE_ACTIVE,
            self::CHANGE_PASSWORD,
            self::CHANGE_NAME,
        ])) {
            return false;
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
            self::LIST,
            self::CREATE => true,
            self::CHANGE_NAME,
            self::CHANGE_PASSWORD,
            self::CHANGE_ACTIVE,
            self::VIEW => $this->canView($user, $subject),
            default => throw new LogicException('Misconfigured voter')
        };
    }

    private function canView(UserInterface $user, DeviceInterface $device): bool
    {
        return $device->getUser() === $user;
    }
}
