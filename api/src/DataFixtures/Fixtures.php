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

namespace App\DataFixtures;

use App\DataFixtures\Factory\DeviceFactory;
use App\DataFixtures\Factory\ReadingFactory;
use App\DataFixtures\Factory\SensorFactory;
use App\DataFixtures\Factory\UserFactory;
use Carbon\CarbonImmutable;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use function Zenstruck\Foundry\faker;

class Fixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        UserFactory::createMany(10, fn(int $i) => ['username' => sprintf('user_%s@example.com', $i)]);

        foreach (UserFactory::all() as $user) {
            DeviceFactory::createMany(faker()->numberBetween(1, 3), ['user' => $user]);
        }

        foreach (DeviceFactory::all() as $device) {
            SensorFactory::createMany(faker()->numberBetween(2, 4), ['device' => $device]);
        }

        CarbonImmutable::setTestNow(CarbonImmutable::now()->setTime(18, 00));
        $sensor = SensorFactory::first();
        for ($i = 0; $i < 400; $i++) {
            $datetime = CarbonImmutable::now()->subMinutes(23);
            CarbonImmutable::setTestNow(new CarbonImmutable($datetime));
            ReadingFactory::createOne(['type' => 'T', 'value' => round(sin($i / 10) * 2 + 20, 2), 'sensor' => $sensor]);
            ReadingFactory::createOne(['type' => 'H', 'value' => round((cos($i / 10) + 1) * 10 + 40, 2), 'sensor' => $sensor]);
        }
    }
}
