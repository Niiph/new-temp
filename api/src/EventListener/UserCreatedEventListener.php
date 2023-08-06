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

use App\Entity\User;
use App\Entity\UserInterface;
use App\Repository\UserRepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsEntityListener;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Event\PrePersistEventArgs;
use Doctrine\ORM\Events;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[AsEntityListener(Events::prePersist, method: 'prePersist', entity: User::class)]
readonly class UserCreatedEventListener
{
    public function __construct(
        private UserPasswordHasherInterface $passwordHasher,
    ) {
    }

    public function prePersist(UserInterface $user, PrePersistEventArgs $event): void
    {
        $hashedPassword = $this->passwordHasher->hashPassword($user, $user->getPlainPassword());
        $user->setPassword($hashedPassword);
    }
}
