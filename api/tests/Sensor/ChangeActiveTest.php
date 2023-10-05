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

namespace App\Tests\Sensor;

use App\DataFixtures\Factory\DeviceFactory;
use App\DataFixtures\Factory\SensorFactory;
use App\DataFixtures\Factory\UserFactory;
use App\Tests\TestCase;

class ChangeActiveTest extends TestCase
{
    public function test_sensor_change_active(): void
    {
        $token = $this->generateToken();
        $user = UserFactory::first();
        $device = DeviceFactory::new(['user' => $user])->create();
        $sensor = SensorFactory::new(['device' => $device, 'active' => false])->create();

        $response = static::createClient()->request(
            'PUT',
            sprintf('/api/sensors/%s/change_active', $sensor->getId()),
            [
                'headers' => ['accept' => ['application/json']],
                'auth_bearer' => $token,
                'json' => ['active' => true],
            ]
        );

        $this->assertResponseIsSuccessful();
        $this->assertTrue($sensor->isActive());

        $response = static::createClient()->request(
            'PUT',
            sprintf('/api/sensors/%s/change_active', $sensor->getId()),
            [
                'headers' => ['accept' => ['application/json']],
                'auth_bearer' => $token,
                'json' => ['active' => false],
            ]
        );

        $this->assertResponseIsSuccessful();
        $this->assertFalse($sensor->isActive());
    }

    public function test_sensor_change_active_as_non_owner(): void
    {
        $token = $this->generateToken();
        $sensor = SensorFactory::createOne();

        $response = static::createClient()->request(
            'PUT',
            sprintf('/api/sensors/%s/change_active', $sensor->getId()),
            [
                'headers' => ['accept' => ['application/json']],
                'auth_bearer' => $token,
                'json' => ['active' => false],
            ]
        );

        $this->assertResponseStatusCodeSame(403);
    }

    public function test_sensor_change_active_as_anonymous(): void
    {
        $sensor = SensorFactory::createOne();

        $response = static::createClient()->request(
            'PUT',
            sprintf('/api/sensors/%s/change_active', $sensor->getId()),
            [
                'headers' => ['accept' => ['application/json']],
                'json' => ['active' => false],
            ]
        );

        $this->assertResponseStatusCodeSame(401);
    }
}
