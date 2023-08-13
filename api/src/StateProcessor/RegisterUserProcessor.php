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

namespace App\StateProcessor;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\DTO\RegisterUserInput;
use App\Entity\User;
use App\Exception\UserAlreadyExistsException;
use App\Repository\UserRepositoryInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

readonly class RegisterUserProcessor implements ProcessorInterface
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private UserRepositoryInterface $userRepository,
    ) {
    }

    /** @param RegisterUserInput $data */
    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = []): void
    {
        $user = $this->userRepository->findBy(['username' => $data->username]);
        if ($user) {
            throw new UserAlreadyExistsException($data->username);
        }

        $user = new User($data->username, $data->password);

        $this->entityManager->persist($user);
        $this->entityManager->flush();
    }
}
