<?php
/*
 * This file is part of the *TBD* package.
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

class ListTest extends TestCase
{
    public function test_devices_list(): void
    {
        $token = $this->generateToken();
        $user = UserFactory::first();
        $device = DeviceFactory::createOne(['user' => $user]);
        SensorFactory::createMany(2, ['device' => $device]);
        $device = DeviceFactory::createOne(['user' => $user]);
        SensorFactory::createMany(2, ['device' => $device]);
        SensorFactory::createOne(['device' => $device, 'active' => false]);
        $device = DeviceFactory::createOne(['user' => $user, 'active' => false]);
        SensorFactory::createMany(2, ['device' => $device]);
        DeviceFactory::createMany(2);

        $response = static::createClient()->request(
            'GET',
            '/api/devices',
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
                "sensors" => [
                    [
                        "id" => "d7129577-b731-361a-84c2-e7327022af80",
                        "name" => "eligendi",
                    ],
                    [
                        "id" => "096cca45-fb7a-3f6c-9ffd-b72e9a9c9533",
                        "name" => "soluta",
                    ],
                ],
            ],
            [
                "id" => "2d942121-da91-31f7-aa6b-89705f5a9240",
                "name" => "tempora",
                "sensors" => [
                    [
                        "id" => "f02e4f04-4c2b-32fb-bbf8-f7ce933d8b9c",
                        "name" => "sed",
                    ],
                    [
                        "id" => "8a67c9ec-319a-33df-bfaf-84a6a4dfcd50",
                        "name" => "voluptas",
                    ],
                ],
            ],
        ];

        $this->assertSame($expectedArray, $response->toArray());
    }

    public function test_devices_list_as_anonymous(): void
    {
        static::createClient()->request(
            'GET',
            '/api/devices',
            [
                'headers' => ['accept' => ['application/json']],
            ]
        );

        $this->assertResponseStatusCodeSame(401);
    }
}
