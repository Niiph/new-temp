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
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Put;
use App\DTO\ChangeActiveInput;
use App\DTO\ChangeNameInput;
use App\DTO\DeviceCreateInput;
use App\DTO\DeviceFullListOutput;
use App\DTO\DeviceOutput;
use App\DTO\DeviceShortListOutput;
use App\DTO\DeviceSimpleOutput;
use App\Repository\DeviceRepository;
use App\StateProcessor\DeviceCreateProcessor;
use App\StateProcessor\ChangeActiveProcessor;
use App\StateProcessor\DeviceChangeNameProcessor;
use App\StateProcessor\SensorChangePasswordProcessor;
use App\StateProvider\OutputCollectionProvider;
use App\StateProvider\OutputItemProvider;
use App\StateProvider\SensorsListProvider;
use App\Util\CreatedAtTrait;
use App\Util\IdentifiableTrait;
use App\Util\ShortUuidGenerator;
use Carbon\CarbonImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
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
            uriTemplate: '/devices/{id}/list_sensors',
            name: 'list_sensors',
            provider: SensorsListProvider::class,
        ),
        new GetCollection(
            uriTemplate: 'devices',
            security: 'is_granted("list_devices", object)',
            output: DeviceShortListOutput::class,
            provider: OutputCollectionProvider::class,
            extraProperties: ['name' => 'devices_list'],
        ),
        new GetCollection(
            uriTemplate: 'devices/full_list',
            security: 'is_granted("list_devices", object)',
            output: DeviceFullListOutput::class,
            provider: OutputCollectionProvider::class,
            extraProperties: ['name' => 'devices_full_list'],
        ),
        new GetCollection(
            uriTemplate: 'devices/simple_list',
            security: 'is_granted("list_devices", object)',
            output: DeviceSimpleOutput::class,
            provider: OutputCollectionProvider::class,
            extraProperties: ['name' => 'devices_simple_list'],
        ),
        new Get(
            uriTemplate: 'devices/{id}',
            security: 'is_granted("device_get", object)',
            provider: OutputItemProvider::class,
        ),
        new Put(
            uriTemplate: '/devices/{id}/change_active',
            security: 'is_granted("device_change_active", object)',
            input: ChangeActiveInput::class,
            output: DeviceOutput::class,
            processor: ChangeActiveProcessor::class,
        ),
        new Put(
            uriTemplate: '/devices/{id}/change_password',
            security: 'is_granted("device_change_password", object)',
            processor: SensorChangePasswordProcessor::class,
        ),
        new Put(
            uriTemplate: '/devices/{id}/change_name',
            security: 'is_granted("device_change_name", object)',
            input: ChangeNameInput::class,
            processor: DeviceChangeNameProcessor::class,
        ),
        new Post(
            security: 'is_granted("device_create")',
            input: DeviceCreateInput::class,
            processor: DeviceCreateProcessor::class,
        ),
    ],
    output: DeviceOutput::class,
)]
#[UniqueEntity(['id', 'shortId'])]
#[Entity(DeviceRepository::class)]
#[Table('devices')]
class Device implements DeviceInterface
{
    use IdentifiableTrait;
    use CreatedAtTrait;

    #[Column(type: 'string')]
    private string $shortId;

    #[Column(type: 'string')]
    private string $name;

    #[Column(type: 'boolean')]
    private bool $active;

    #[ManyToOne(targetEntity: UserInterface::class, cascade: ['persist', 'remove'], inversedBy: 'devices')]
    private UserInterface $user;

    #[Column(type: 'string')]
    private string $devicePassword;

    #[OrderBy(['active' => 'DESC', 'name' => 'ASC'])]
    #[OneToMany(mappedBy: 'device', targetEntity: SensorInterface::class, cascade: ['persist', 'remove'], orphanRemoval: true)]
    private Collection $sensors;

    #[OneToMany(mappedBy: 'device', targetEntity: DeviceTokenInterface::class, cascade: ['persist', 'remove'], orphanRemoval: true)]
    private Collection $deviceTokens;

    public function __construct(
        UserInterface  $user,
        string         $name,
        bool           $active = false,
        ?UuidInterface $id = null
    ) {
        $this->user           = $user;
        $this->name           = $name;
        $this->active         = $active;
        $this->id             = $id ?? Uuid::uuid4();
        $this->shortId        = ShortUuidGenerator::getShortUuid($this->id);
        $this->devicePassword = $this->generateDevicePassword();

        $this->createdAt = CarbonImmutable::now();

        $this->sensors      = new ArrayCollection();
        $this->deviceTokens = new ArrayCollection();
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function isActive(): bool
    {
        return $this->active;
    }

    public function setActive(bool $active): void
    {
        $this->active = $active;
    }

    public function getShortId(): string
    {
        return $this->shortId;
    }

    public function getUser(): UserInterface
    {
        return $this->user;
    }

    public function getDevicePassword(): ?string
    {
        return $this->devicePassword;
    }

    public function generateDevicePassword(): string
    {
        $this->devicePassword = ShortUuidGenerator::getShortUuid(Uuid::uuid4());

        return $this->devicePassword;
    }

    /** @return Collection<SensorInterface> */
    public function getSensors(): Collection
    {
        return $this->sensors;
    }

    public function addSensor(SensorInterface $sensor): void
    {
        if (!$this->sensors->contains($sensor)) {
            $this->sensors->add($sensor);
        }
    }

    public function removeSensor(SensorInterface $sensor): void
    {
        if ($this->sensors->contains($sensor)) {
            $this->sensors->removeElement($sensor);
        }
    }

    /** @return Collection<DeviceTokenInterface> */
    public function getDeviceTokens(): Collection
    {
        return $this->deviceTokens;
    }

    public function addDeviceToken(DeviceTokenInterface $deviceToken): void
    {
        if (!$this->deviceTokens->contains($deviceToken)) {
            $this->deviceTokens->add($deviceToken);
        }
    }

    public function removeDeviceToken(DeviceTokenInterface $deviceToken): void
    {
        if ($this->deviceTokens->contains($deviceToken)) {
            $this->deviceTokens->removeElement($deviceToken);
        }
    }

    public function isTokenValid(string $token): bool
    {
        $deviceToken = $this->deviceTokens->filter(static function (DeviceTokenInterface $deviceToken) use ($token) {
            return $deviceToken->getToken() === $token;
        })->first();

        if (!$deviceToken instanceof DeviceTokenInterface) {
            return false;
        }

        return $deviceToken->getExpirationTime()->gte(CarbonImmutable::now());
    }

    public function isOwner(UserInterface $user): bool
    {
        return $this->getUser() === $user;
    }
}
