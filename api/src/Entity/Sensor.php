<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Constraints\NotBlank;

#[ApiResource(mercure: true)]
#[Entity]
class Sensor
{
    #[Column(type: 'uuid', unique: true), Id, GeneratedValue(strategy: 'NONE')]
    private ?int $id = null;

    #[Column]
    #[NotBlank]
    public string $name = '';

    public function getId(): int
    {
        return $this->id;
    }
}
