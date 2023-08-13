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

namespace App\Entity;

use App\Repository\SensorRepository;
use App\Util\CreatedAtTrait;
use App\Util\IdentifiableTrait;
use App\Util\StringLengthUtil;
use Carbon\CarbonImmutable;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\Table;
use Doctrine\ORM\Mapping\UniqueConstraint;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

#[UniqueEntity('id')]
#[Entity(repositoryClass: SensorRepository::class)]
#[Table('sensor_settings')]
#[UniqueConstraint(columns: ['sensor_id', 'type'])]
class SensorSettings implements SensorSettingsInterface
{
    use IdentifiableTrait;
    use CreatedAtTrait;

    #[Column(type: 'integer', nullable: true)]
    private ?int $minimum;

    #[Column(type: 'integer', nullable: true)]
    private ?int $maximum;

    #[Column(type: 'string', length: StringLengthUtil::TYPE_NAME)]
    private string $type;

    #[ManyToOne(targetEntity: SensorInterface::class, cascade: ['persist'], inversedBy: 'sensorSettings')]
    #[JoinColumn(nullable: false, onDelete: 'CASCADE')]
    private SensorInterface $sensor;

    public function __construct(
        ?int $minimum,
        ?int $maximum,
        string $type,
        SensorInterface $sensor,
        ?UuidInterface $id = null
    ) {
        $this->minimum = $minimum;
        $this->maximum = $maximum;
        $this->type = $type;
        $this->sensor = $sensor;
        $this->id = $id ?? Uuid::uuid4();

        $this->createdAt = CarbonImmutable::now();
    }

    public function getMinimum(): ?int
    {
        return $this->minimum;
    }

    public function setMinimum(?int $minimum): void
    {
        $this->minimum = $minimum;
    }

    public function getMaximum(): ?int
    {
        return $this->maximum;
    }

    public function setMaximum(?int $maximum): void
    {
        $this->maximum = $maximum;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function setType(string $type): void
    {
        $this->type = $type;
    }

    public function getSensor(): SensorInterface
    {
        return $this->sensor;
    }

    public function setSensor(SensorInterface $sensor): void
    {
        $this->sensor = $sensor;
    }
}
