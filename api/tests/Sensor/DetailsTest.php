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
use Zenstruck\Foundry\Instantiator;

class DetailsTest extends TestCase
{
    public function test_sensor_details(): void
    {
        $token = $this->generateToken();
        $user = UserFactory::first();
        $device = DeviceFactory::new(['user' => $user])->create();
        $sensor = SensorFactory::new(['device' => $device])->create();

        $response = static::createClient()->request(
            'GET',
            sprintf('/api/sensors/%s/details', $sensor->getId()),
            [
                'headers' => ['accept' => ['application/json']],
                'auth_bearer' => $token,
            ]
        );

        $this->assertResponseIsSuccessful();

        $expectedArray = [
            'id' => '8a67c9ec-319a-33df-bfaf-84a6a4dfcd50',
            'name' => 'voluptas',
            'active' => true,
            'pin' => 1,
            'address' => '9, da, 50, e0, 3, 38, 3d, 7f',
            'minimum' => null,
            'maximum' => null,
            'device' => [
                'id' => '2d942121-da91-31f7-aa6b-89705f5a9240',
                'name' => 'tempora',
            ],
        ];

        $this->assertSame($expectedArray, $response->toArray());
    }

    public function test_sensor_details_as_non_owner(): void
    {
        $token = $this->generateToken();
        $sensor = SensorFactory::createOne();

        $response = static::createClient()->request(
            'GET',
            sprintf('/api/sensors/%s/details', $sensor->getId()),
            [
                'headers' => ['accept' => ['application/json']],
                'auth_bearer' => $token,
            ]
        );

        $this->assertResponseStatusCodeSame(403);
    }

    public function test_sensor_details_as_anonymous(): void
    {
        $sensor = SensorFactory::createOne();

        $response = static::createClient()->request(
            'GET',
            sprintf('/api/sensors/%s/details', $sensor->getId()),
            [
                'headers' => ['accept' => ['application/json']],
            ]
        );

        $this->assertResponseStatusCodeSame(401);
    }
}
