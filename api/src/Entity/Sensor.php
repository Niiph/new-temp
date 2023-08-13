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

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Put;
use App\DTO\ChangeActiveInput;
use App\DTO\ChangeNameInput;
use App\DTO\ReadingInput;
use App\DTO\SensorChangeAddressInput;
use App\DTO\SensorChangeDeviceInput;
use App\DTO\SensorChangeMaximumInput;
use App\DTO\SensorChangeMinimumInput;
use App\DTO\SensorChangePinInput;
use App\DTO\SensorCreateInput;
use App\DTO\SensorExternalOutput;
use App\DTO\SensorOutput;
use App\DTO\SensorReadingsOutput;
use App\Repository\SensorRepository;
use App\StateProcessor\ChangeActiveProcessor;
use App\StateProcessor\ReadingProcessor;
use App\StateProcessor\SensorChangeAddressProcessor;
use App\StateProcessor\SensorChangeDeviceProcessor;
use App\StateProcessor\SensorChangeMaximumProcessor;
use App\StateProcessor\SensorChangeMinimumProcessor;
use App\StateProcessor\SensorChangeNameProcessor;
use App\StateProcessor\SensorChangePinProcessor;
use App\StateProcessor\SensorCreateProcessor;
use App\StateProvider\OutputItemProvider;
use App\StateProvider\SensorExternalProvider;
use App\StateProvider\SensorReadingsProvider;
use App\Util\CreatedAtTrait;
use App\Util\IdentifiableTrait;
use Carbon\CarbonImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\Selectable;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\OneToMany;
use Doctrine\ORM\Mapping\OrderBy;
use Doctrine\ORM\Mapping\Table;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

#[ApiResource(
    operations: [
        new Get(
            output: SensorExternalOutput::class,
            provider: SensorExternalProvider::class,
        ),
        new Put(
            uriTemplate: 'sensors/{id}/add_reading',
            status: 204,
            input: ReadingInput::class,
            name: 'add_reading',
            processor: ReadingProcessor::class,
        ),
        new Get(
            uriTemplate: 'sensors/{id}/details',
            security: 'is_granted("sensor_get", object)',
            provider: OutputItemProvider::class,
        ),
        new Put(
            uriTemplate: '/sensors/{id}/change_active',
            security: 'is_granted("sensor_change_active", object)',
            input: ChangeActiveInput::class,
            output: Sensor::class,
            processor: ChangeActiveProcessor::class,
        ),
        new Put(
            uriTemplate: '/sensors/{id}/change_name',
            security: 'is_granted("sensor_change_name", object)',
            input: ChangeNameInput::class,
            processor: SensorChangeNameProcessor::class,
        ),
        new Put(
            uriTemplate: '/sensors/{id}/change_minimum',
            security: 'is_granted("sensor_change_minimum", object)',
            input: SensorChangeMinimumInput::class,
            processor: SensorChangeMinimumProcessor::class,
        ),
        new Put(
            uriTemplate: '/sensors/{id}/change_maximum',
            security: 'is_granted("sensor_change_maximum", object)',
            input: SensorChangeMaximumInput::class,
            processor: SensorChangeMaximumProcessor::class,
        ),
        new Put(
            uriTemplate: '/sensors/{id}/change_pin',
            security: 'is_granted("sensor_change_pin", object)',
            input: SensorChangePinInput::class,
            processor: SensorChangePinProcessor::class,
        ),
        new Put(
            uriTemplate: '/sensors/{id}/change_device',
            security: 'is_granted("sensor_change_device", object)',
            input: SensorChangeDeviceInput::class,
            processor: SensorChangeDeviceProcessor::class,
        ),
        new Put(
            uriTemplate: '/sensors/{id}/change_address',
            security: 'is_granted("sensor_change_address", object)',
            input: SensorChangeAddressInput::class,
            processor: SensorChangeAddressProcessor::class,
        ),
        new Post(
            security: 'is_granted("sensor_create")',
            input: SensorCreateInput::class,
            processor: SensorCreateProcessor::class,
        ),
        new Get(
            uriTemplate: 'sensors/{id}/readings',
            output: SensorReadingsOutput::class,
            provider: SensorReadingsProvider::class,
        ),
    ],
    output: SensorOutput::class,
)]
#[UniqueEntity('id')]
#[Entity(repositoryClass: SensorRepository::class)]
#[Table('sensors')]
class Sensor implements SensorInterface
{
    use IdentifiableTrait;
    use CreatedAtTrait;

    #[Column(type: 'string')]
    private string $name;

    #[Column(type: 'integer', nullable: true)]
    private ?int $pin;

    #[Column(type: 'string', nullable: true)]
    private ?string $address;

    #[Column(type: 'integer', nullable: true)]
    private ?int $minimum;

    #[Column(type: 'integer', nullable: true)]
    private ?int $maximum;

    #[Column(type: 'boolean')]
    private bool $active;

    #[ManyToOne(targetEntity: DeviceInterface::class, cascade: ['persist'], inversedBy: 'sensors')]
    private DeviceInterface $device;

    #[OrderBy(['createdAt' => 'ASC'])]
    #[OneToMany(mappedBy: 'sensor', targetEntity: ReadingInterface::class, cascade: ['persist', 'remove'], fetch: 'EXTRA_LAZY', orphanRemoval: true)]
    private Collection&Selectable $readings;

    #[OneToMany(mappedBy: 'sensor', targetEntity: SensorSettingsInterface::class, cascade: ['persist', 'remove'], orphanRemoval: true)]
    private Collection $sensorSettings;

    public function __construct(
        DeviceInterface $device,
        string $name,
        ?int $pin = null,
        ?string $address = null,
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

    public function getPin(): ?int
    {
        return $this->pin;
    }

    public function setPin(?int $pin): void
    {
        $this->pin = $pin;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(?string $address): void
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

    public function getReadings(): Collection&Selectable
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

    public function isOwner(UserInterface $user): bool
    {
        return $this->getDevice()->getUser() === $user;
    }
}
