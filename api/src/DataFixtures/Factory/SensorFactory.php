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

namespace App\DataFixtures\Factory;

use App\Entity\Sensor;
use App\Repository\SensorRepository;
use Ramsey\Uuid\Uuid;
use Zenstruck\Foundry\ModelFactory;
use Zenstruck\Foundry\Proxy;
use Zenstruck\Foundry\RepositoryProxy;

/**
 * @extends ModelFactory<Sensor>
 *
 * @method        Sensor|Proxy                     create(array|callable $attributes = [])
 * @method static Sensor|Proxy                     createOne(array $attributes = [])
 * @method static Sensor|Proxy                     find(object|array|mixed $criteria)
 * @method static Sensor|Proxy                     findOrCreate(array $attributes)
 * @method static Sensor|Proxy                     first(string $sortedField = 'id')
 * @method static Sensor|Proxy                     last(string $sortedField = 'id')
 * @method static Sensor|Proxy                     random(array $attributes = [])
 * @method static Sensor|Proxy                     randomOrCreate(array $attributes = [])
 * @method static SensorRepository|RepositoryProxy repository()
 * @method static Sensor[]|Proxy[]                 all()
 * @method static Sensor[]|Proxy[]                 createMany(int $number, array|callable $attributes = [])
 * @method static Sensor[]|Proxy[]                 createSequence(iterable|callable $sequence)
 * @method static Sensor[]|Proxy[]                 findBy(array $attributes)
 * @method static Sensor[]|Proxy[]                 randomRange(int $min, int $max, array $attributes = [])
 * @method static Sensor[]|Proxy[]                 randomSet(int $number, array $attributes = [])
 *
 * @phpstan-method        Proxy<Sensor> create(array|callable $attributes = [])
 * @phpstan-method static Proxy<Sensor> createOne(array $attributes = [])
 * @phpstan-method static Proxy<Sensor> find(object|array|mixed $criteria)
 * @phpstan-method static Proxy<Sensor> findOrCreate(array $attributes)
 * @phpstan-method static Proxy<Sensor> first(string $sortedField = 'id')
 * @phpstan-method static Proxy<Sensor> last(string $sortedField = 'id')
 * @phpstan-method static Proxy<Sensor> random(array $attributes = [])
 * @phpstan-method static Proxy<Sensor> randomOrCreate(array $attributes = [])
 * @phpstan-method static RepositoryProxy<Sensor> repository()
 * @phpstan-method static list<Proxy<Sensor>> all()
 * @phpstan-method static list<Proxy<Sensor>> createMany(int $number, array|callable $attributes = [])
 * @phpstan-method static list<Proxy<Sensor>> createSequence(iterable|callable $sequence)
 * @phpstan-method static list<Proxy<Sensor>> findBy(array $attributes)
 * @phpstan-method static list<Proxy<Sensor>> randomRange(int $min, int $max, array $attributes = [])
 * @phpstan-method static list<Proxy<Sensor>> randomSet(int $number, array $attributes = [])
 */
final class SensorFactory extends ModelFactory
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
        $address = [];
        for ($i = 0; $i < 8; $i++) {
            $address[] = dechex(self::faker()->numberBetween(0, 255));
        }

        return [
            'device' => DeviceFactory::new(),
            'active' => true,
            'name' => self::faker()->word(),
            'pin' => self::faker()->numberBetween(1, 30),
            'address' => implode(', ', $address),
            'id' => Uuid::fromString(self::faker()->uuid()),
        ];
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
     */
    protected function initialize(): self
    {
        return $this
            // ->afterInstantiate(function(Sensor $sensor): void {})
        ;
    }

    protected static function getClass(): string
    {
        return Sensor::class;
    }
}
