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

use App\Entity\Reading;
use Doctrine\ORM\EntityRepository;
use Zenstruck\Foundry\ModelFactory;
use Zenstruck\Foundry\Proxy;
use Zenstruck\Foundry\RepositoryProxy;

/**
 * @extends ModelFactory<Reading>
 *
 * @method        Reading|Proxy                    create(array|callable $attributes = [])
 * @method static Reading|Proxy                    createOne(array $attributes = [])
 * @method static Reading|Proxy                    find(object|array|mixed $criteria)
 * @method static Reading|Proxy                    findOrCreate(array $attributes)
 * @method static Reading|Proxy                    first(string $sortedField = 'id')
 * @method static Reading|Proxy                    last(string $sortedField = 'id')
 * @method static Reading|Proxy                    random(array $attributes = [])
 * @method static Reading|Proxy                    randomOrCreate(array $attributes = [])
 * @method static EntityRepository|RepositoryProxy repository()
 * @method static Reading[]|Proxy[]                all()
 * @method static Reading[]|Proxy[]                createMany(int $number, array|callable $attributes = [])
 * @method static Reading[]|Proxy[]                createSequence(iterable|callable $sequence)
 * @method static Reading[]|Proxy[]                findBy(array $attributes)
 * @method static Reading[]|Proxy[]                randomRange(int $min, int $max, array $attributes = [])
 * @method static Reading[]|Proxy[]                randomSet(int $number, array $attributes = [])
 *
 * @phpstan-method        Proxy<Reading> create(array|callable $attributes = [])
 * @phpstan-method static Proxy<Reading> createOne(array $attributes = [])
 * @phpstan-method static Proxy<Reading> find(object|array|mixed $criteria)
 * @phpstan-method static Proxy<Reading> findOrCreate(array $attributes)
 * @phpstan-method static Proxy<Reading> first(string $sortedField = 'id')
 * @phpstan-method static Proxy<Reading> last(string $sortedField = 'id')
 * @phpstan-method static Proxy<Reading> random(array $attributes = [])
 * @phpstan-method static Proxy<Reading> randomOrCreate(array $attributes = [])
 * @phpstan-method static RepositoryProxy<Reading> repository()
 * @phpstan-method static list<Proxy<Reading>> all()
 * @phpstan-method static list<Proxy<Reading>> createMany(int $number, array|callable $attributes = [])
 * @phpstan-method static list<Proxy<Reading>> createSequence(iterable|callable $sequence)
 * @phpstan-method static list<Proxy<Reading>> findBy(array $attributes)
 * @phpstan-method static list<Proxy<Reading>> randomRange(int $min, int $max, array $attributes = [])
 * @phpstan-method static list<Proxy<Reading>> randomSet(int $number, array $attributes = [])
 */
final class ReadingFactory extends ModelFactory
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
            'sensor' => SensorFactory::new(),
            'type' => self::faker()->randomLetter(),
            'value' => self::faker()->randomFloat(),
        ];
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
     */
    protected function initialize(): self
    {
        return $this
            // ->afterInstantiate(function(Reading $reading): void {})
        ;
    }

    protected static function getClass(): string
    {
        return Reading::class;
    }
}
