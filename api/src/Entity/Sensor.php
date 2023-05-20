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

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Put;
use App\DTO\ReadingInput;
use App\DTO\SensorOutput;
use App\Repository\SensorRepository;
use App\StateProcessor\ReadingProcessor;
use App\StateProvider\OutputItemProvider;
use App\Util\CreatedAtTrait;
use App\Util\IdentifiableTrait;
use Carbon\CarbonImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\OneToMany;
use Doctrine\ORM\Mapping\Table;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

#[ApiResource(
    operations: [
    //        new Get(),
    //        new Post(),
    //        new GetCollection(),
    //        new Delete(),
        new Put(
            uriTemplate: 'sensors/{id}/add_reading',
            status: 204,
            input: ReadingInput::class,
            name: 'add_reading',
            processor: ReadingProcessor::class,
        ),
        new Get(
            security: 'is_granted("sensor_get", object)',
            output: SensorOutput::class,
            provider: OutputItemProvider::class,
        ),
    ]
)
]
#[UniqueEntity('id')]
#[Entity(repositoryClass: SensorRepository::class)]
#[Table('sensors')]
class Sensor implements SensorInterface
{
    use IdentifiableTrait;
    use CreatedAtTrait;

    #[Column(type: 'string')]
    private string $name;

    #[Column(type: 'integer')]
    private int $pin;

    #[Column(type: 'string')]
    private string $address;

    #[Column(type: 'integer', nullable: true)]
    private ?int $minimum;

    #[Column(type: 'integer', nullable: true)]
    private ?int $maximum;

    #[Column(type: 'boolean')]
    private bool $active;

    #[ManyToOne(targetEntity: DeviceInterface::class, cascade: ['persist'], inversedBy: 'sensors')]
    private DeviceInterface $device;

    #[OneToMany(mappedBy: 'sensor', targetEntity: ReadingInterface::class, cascade: ['persist', 'remove'], fetch: 'EXTRA_LAZY', orphanRemoval: true)]
    private Collection $readings;

    #[OneToMany(mappedBy: 'sensor', targetEntity: SensorSettingsInterface::class, cascade: ['persist', 'remove'], orphanRemoval: true)]
    private Collection $sensorSettings;

    public function __construct(
        DeviceInterface $device,
        string $name,
        int $pin,
        string $address,
        ?int $minimum = null,
        ?int $maximum = null,
        bool $active = false,
        ?UuidInterface $id = null
    ) {
        $this->device = $device;
        $this->name   = $name;
        $this->pin     = $pin;
        $this->address = $address;
        $this->minimum = $minimum;
        $this->maximum = $maximum;
        $this->active  = $active;
        $this->id      = $id ?? Uuid::uuid4();

        $this->createdAt = CarbonImmutable::now();

        $this->readings = new ArrayCollection();
        $this->sensorSettings = new ArrayCollection();
    }

    public function getDevice(): DeviceInterface
    {
        return $this->device;
    }

    public function setDevice(DeviceInterface $device): void
    {
        $this->device = $device;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getPin(): int
    {
        return $this->pin;
    }

    public function setPin(int $pin): void
    {
        $this->pin = $pin;
    }

    public function getAddress(): string
    {
        return $this->address;
    }

    public function setAddress(string $address): void
    {
        $this->address = $address;
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

    public function isActive(): bool
    {
        return $this->active;
    }

    public function setActive(bool $active): void
    {
        $this->active = $active;
    }

    public function getReadings(): Collection
    {
        return $this->readings;
    }

    public function addReading(ReadingInterface $reading): void
    {
        if (!$this->readings->contains($reading)) {
            $this->readings->add($reading);
        }
    }

    public function removeReading(ReadingInterface $reading): void
    {
        if ($this->readings->contains($reading)) {
            $this->readings->removeElement($reading);
        }
    }

    /** @return Collection<SensorSettingsInterface> */
    public function getSensorSettings(): Collection
    {
        return $this->sensorSettings;
    }

    public function addSensorSettings(SensorSettingsInterface $sensorSettings): void
    {
        if (!$this->sensorSettings->contains($sensorSettings)) {
            $this->sensorSettings->add($sensorSettings);
        }
    }

    public function removeSensorSettings(SensorSettingsInterface $sensorSettings): void
    {
        if ($this->sensorSettings->contains($sensorSettings)) {
            $this->sensorSettings->removeElement($sensorSettings);
        }
    }
}
