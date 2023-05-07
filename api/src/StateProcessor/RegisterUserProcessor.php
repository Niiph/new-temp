<?php

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
        private EntityManagerInterface      $entityManager,
        private UserRepositoryInterface     $userRepository,
        private UserPasswordHasherInterface $passwordHasher,
    ) {
    }

    /** @param RegisterUserInput $data */
    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = []): void
    {
        $user = $this->userRepository->findBy(['username' => $data->username]);
        if ($user) {
            throw new UserAlreadyExistsException($data->username);
        }

        $user = new User($data->username);

        $hashedPassword = $this->passwordHasher->hashPassword($user, $data->password);
        $user->setPassword($hashedPassword);

        $this->entityManager->persist($user);
        $this->entityManager->flush();
    }
}
