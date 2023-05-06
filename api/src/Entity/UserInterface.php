<?php

declare(strict_types=1);

namespace App\Entity;

use App\Util\AccountRole;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface as BaseUserInterface;

interface UserInterface extends BaseUserInterface, PasswordAuthenticatedUserInterface
{
    public function getUsername(): string;

    public function setUsername(string $username): void;

    public function getPassword(): ?string;

    public function setPassword(?string $password): void;

//    public function getPlainPassword(): ?string;
//
//    public function setPlainPassword(?string $plainPassword): void;

    public function hasRole(AccountRole $accountRole): bool;

    /* @return Collection<DeviceInterface> */
    public function getDevices(): Collection;

    public function addDevice(DeviceInterface $device): void;

    public function removeDevice(DeviceInterface $device): void;
}
