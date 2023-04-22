<?php

declare(strict_types=1);

namespace App\Util;

use ApiPlatform\Metadata\ApiProperty;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Ramsey\Uuid\UuidInterface;

trait IdentifiableTrait
{
    #[ApiProperty(identifier: true)]
    #[Column(type: 'uuid', unique: true), Id, GeneratedValue(strategy: 'NONE')]
    private UuidInterface $id;

    public function getId(): UuidInterface
    {
        return $this->id;
    }
}
