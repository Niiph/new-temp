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

namespace App\Tests\Device;

use App\DataFixtures\Factory\DeviceFactory;
use App\DataFixtures\Factory\SensorFactory;
use App\DataFixtures\Factory\UserFactory;
use App\Tests\TestCase;

class SimpleListTest extends TestCase
{
    public function test_devices_simple_list(): void
    {
        $token = $this->generateToken();
        $user = UserFactory::first();
        $device = DeviceFactory::createOne(['user' => $user]);
        SensorFactory::createMany(2, ['device' => $device]);
        $device = DeviceFactory::createOne(['user' => $user]);
        SensorFactory::createMany(2, ['device' => $device]);
        DeviceFactory::createMany(2);

        $response = static::createClient()->request(
            'GET',
            '/api/devices/simple_list',
            [
                'headers' => ['accept' => ['application/json']],
                'auth_bearer' => $token,
            ]
        );

        $this->assertResponseIsSuccessful();

        $expectedArray = [
            [
                "id" => "0f7926d1-5473-3371-b3e2-92b6331e0108",
                "name" => "fugit",
            ],
            [
                "id" => "2d942121-da91-31f7-aa6b-89705f5a9240",
                "name" => "tempora",
            ]
        ];

        $this->assertSame($expectedArray, $response->toArray());
    }

    public function test_devices_simple_list_as_anonymous(): void
    {
        static::createClient()->request(
            'GET',
            '/api/devices/simple_list',
            [
                'headers' => ['accept' => ['application/json']],
            ]
        );

        $this->assertResponseStatusCodeSame(401);
    }
}
