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
    public const CHANGE_ACTIVE = 'sensor_change_active';
    public const CREATE = 'sensor_create';
    public const CHANGE_NAME = 'sensor_change_name';
    public const CHANGE_MINIMUM = 'sensor_change_minimum';
    public const CHANGE_MAXIMUM = 'sensor_change_maximum';
    public const CHANGE_PIN = 'sensor_change_pin';
    public const CHANGE_DEVICE = 'sensor_change_device';
    public const CHANGE_ADDRESS = 'sensor_change_address';

    protected function supports(string $attribute, mixed $subject): bool
    {
        if (!$subject && self::CREATE === $attribute) {
            return true;
        }

        if (!in_array($attribute, [
            self::VIEW,
            self::CHANGE_ACTIVE,
            self::CHANGE_NAME,
            self::CHANGE_MINIMUM,
            self::CHANGE_MAXIMUM,
            self::CHANGE_PIN,
            self::CHANGE_DEVICE,
            self::CHANGE_ADDRESS,
        ])) {
            return false;
        }

        return $subject instanceof SensorInterface;
    }

    /** @param SensorInterface $subject */
    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();
        if (!$user instanceof UserInterface) {
            return false;
        }

        return match ($attribute) {
            self::CHANGE_ACTIVE,
            self::CHANGE_NAME,
            self::CHANGE_MINIMUM,
            self::CHANGE_MAXIMUM,
            self::CHANGE_PIN,
            self::CHANGE_DEVICE,
            self::CHANGE_ADDRESS,
            self::VIEW => $subject->isOwner($user),
            self::CREATE => true,
            default => throw new LogicException('Misconfigured voter')
        };
    }
}
