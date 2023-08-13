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

namespace App\DataFixtures\Factory;

use App\Entity\Device;
use App\Repository\DeviceRepository;
use Ramsey\Uuid\Uuid;
use Zenstruck\Foundry\ModelFactory;
use Zenstruck\Foundry\Proxy;
use Zenstruck\Foundry\RepositoryProxy;

/**
 * @extends ModelFactory<Device>
 *
 * @method        Device|Proxy                     create(array|callable $attributes = [])
 * @method static Device|Proxy                     createOne(array $attributes = [])
 * @method static Device|Proxy                     find(object|array|mixed $criteria)
 * @method static Device|Proxy                     findOrCreate(array $attributes)
 * @method static Device|Proxy                     first(string $sortedField = 'id')
 * @method static Device|Proxy                     last(string $sortedField = 'id')
 * @method static Device|Proxy                     random(array $attributes = [])
 * @method static Device|Proxy                     randomOrCreate(array $attributes = [])
 * @method static DeviceRepository|RepositoryProxy repository()
 * @method static Device[]|Proxy[]                 all()
 * @method static Device[]|Proxy[]                 createMany(int $number, array|callable $attributes = [])
 * @method static Device[]|Proxy[]                 createSequence(iterable|callable $sequence)
 * @method static Device[]|Proxy[]                 findBy(array $attributes)
 * @method static Device[]|Proxy[]                 randomRange(int $min, int $max, array $attributes = [])
 * @method static Device[]|Proxy[]                 randomSet(int $number, array $attributes = [])
 *
 * @phpstan-method        Proxy<Device> create(array|callable $attributes = [])
 * @phpstan-method static Proxy<Device> createOne(array $attributes = [])
 * @phpstan-method static Proxy<Device> find(object|array|mixed $criteria)
 * @phpstan-method static Proxy<Device> findOrCreate(array $attributes)
 * @phpstan-method static Proxy<Device> first(string $sortedField = 'id')
 * @phpstan-method static Proxy<Device> last(string $sortedField = 'id')
 * @phpstan-method static Proxy<Device> random(array $attributes = [])
 * @phpstan-method static Proxy<Device> randomOrCreate(array $attributes = [])
 * @phpstan-method static RepositoryProxy<Device> repository()
 * @phpstan-method static list<Proxy<Device>> all()
 * @phpstan-method static list<Proxy<Device>> createMany(int $number, array|callable $attributes = [])
 * @phpstan-method static list<Proxy<Device>> createSequence(iterable|callable $sequence)
 * @phpstan-method static list<Proxy<Device>> findBy(array $attributes)
 * @phpstan-method static list<Proxy<Device>> randomRange(int $min, int $max, array $attributes = [])
 * @phpstan-method static list<Proxy<Device>> randomSet(int $number, array $attributes = [])
 */
final class DeviceFactory extends ModelFactory
{
    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#factories-as-services
     *
     * @todo inject services if required
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#model-factories
     *
     * @todo add your default values here
     */
    protected function getDefaults(): array
    {
        return [
            'user' => UserFactory::new(),
            'active' => true,
            'name' => self::faker()->word(),
            'id' => Uuid::fromString(self::faker()->uuid()),
        ];
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
     */
    protected function initialize(): self
    {
        return $this
            // ->afterInstantiate(function(Device $device): void {})
        ;
    }

    protected static function getClass(): string
    {
        return Device::class;
    }
}
