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

use ApiPlatform\Action\PlaceholderAction;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Post;
use App\DTO\DeviceTokenInput;
use App\DTO\DeviceTokenOutput;
use App\StateProcessor\DeviceTokenProcessor;
use App\Util\CreatedAtTrait;
use App\Util\IdentifiableTrait;
use Carbon\CarbonImmutable;
use Carbon\CarbonInterface;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\Table;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

#[ApiResource(
    operations: [
        new Post(
            uriTemplate: 'time',
            input: DeviceTokenInput::class,
            output: DeviceTokenOutput::class,
            name: 'time',
            processor: DeviceTokenProcessor::class,
        ),
        new GetCollection(
            uriTemplate: 'blank',
            output: false,
            name: 'blank',
        ),
    ]
)]
#[UniqueEntity('id')]
#[Entity]
#[Table('device_tokens')]
class DeviceToken implements DeviceTokenInterface
{
    use IdentifiableTrait;
    use CreatedAtTrait;

    #[ManyToOne(targetEntity: DeviceInterface::class, cascade: ['persist'], inversedBy: 'deviceTokens')]
    #[JoinColumn(nullable: false, onDelete: 'CASCADE')]
    private DeviceInterface $device;

    #[Column(type: 'carbon_immutable')]
    private CarbonInterface $expirationTime;

    #[Column(type: 'string')]
    private string $token;

    public function __construct(
        DeviceInterface $device,
        string $validTime,
        ?UuidInterface $id = null
    ) {
        $this->device = $device;
        $this->id = $id ?? Uuid::uuid4();

        $this->createdAt = CarbonImmutable::now();
        $this->expirationTime = CarbonImmutable::now()->add($validTime);

        $this->token = hash(
            'sha256',
            sprintf(
                '%s_%s_%s',
                $device->getDevicePassword(),
                $device->getShortId(),
                $this->createdAt->toIso8601String()
            )
        );
    }

    public function getDevice(): DeviceInterface
    {
        return $this->device;
    }

    public function setDevice(DeviceInterface $device): void
    {
        $this->device = $device;
    }

    public function getExpirationTime(): CarbonInterface
    {
        return $this->expirationTime;
    }

    public function getToken(): string
    {
        return $this->token;
    }

    public function isValid(): bool
    {
        return $this->expirationTime->gte(CarbonImmutable::now());
    }
}
