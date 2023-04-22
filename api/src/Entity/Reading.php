<?php

declare(strict_types=1);

namespace App\Entity;

use ApiPlatform\Metadata\ApiProperty;
use App\Util\CreatedAtTrait;
use Carbon\CarbonImmutable;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\Table;

#[Entity]
#[Table('readings')]
class Reading implements ReadingInterface
{
    use CreatedAtTrait;

    #[ApiProperty(identifier: true)]
    #[Column(unique: true), Id, GeneratedValue]
    private int $id;

    #[Column(type: 'float', precision: 2,)]
    private float $value;

    #[ManyToOne(targetEntity: SensorInterface::class, cascade: ['persist'], inversedBy: 'readings')]
    #[JoinColumn(nullable: false, onDelete: 'CASCADE')]
    private SensorInterface $sensor;

    public function __construct(
        float           $value,
        SensorInterface $sensor,
    ) {
        $this->value  = $value;
        $this->sensor = $sensor;

        $this->createdAt = CarbonImmutable::now();
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getValue(): float
    {
        return $this->value;
    }

    public function getSensor(): SensorInterface
    {
        return $this->sensor;
    }
}
