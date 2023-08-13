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
use Zenstruck\Foundry\Instantiator;

class ViewTest extends TestCase
{
    public function test_device_view(): void
    {
        $token = $this->generateToken();
        $user = UserFactory::first();
        $device = DeviceFactory::new(['user' => $user, 'devicePassword' => 'XG9AEKPTY4U6FYM3GRQ9HQ31J2'])
            ->instantiateWith((new Instantiator())->alwaysForceProperties(['devicePassword']))->create();
        SensorFactory::createMany(2, ['device' => $device]);

        $response = static::createClient()->request(
            'GET',
            sprintf('/api/devices/%s', $device->getId()),
            [
                'headers' => ['accept' => ['application/json']],
                'auth_bearer' => $token,
            ]
        );

        $this->assertResponseIsSuccessful();

        $expectedArray = [
            "id" => "2d942121-da91-31f7-aa6b-89705f5a9240",
            "name" => "tempora",
            "active" => true,
            "shortId" => "75SXGTUV2CN1SFFYWF4ASPPSN",
            'password' => 'XG9AEKPTY4U6FYM3GRQ9HQ31J2',
            "sensors" => [
                [
                    "id" => "f02e4f04-4c2b-32fb-bbf8-f7ce933d8b9c",
                    "name" => "sed",
                    "active" => true,
                ],
                [
                    "id" => "8a67c9ec-319a-33df-bfaf-84a6a4dfcd50",
                    "name" => "voluptas",
                    "active" => true,
                ],
            ],
        ];

        $this->assertSame($expectedArray, $response->toArray());
    }

    public function test_device_view_as_non_owner(): void
    {
        $token = $this->generateToken();
        $device = DeviceFactory::createOne();

        $response = static::createClient()->request(
            'GET',
            sprintf('/api/devices/%s', $device->getId()),
            [
                'headers' => ['accept' => ['application/json']],
                'auth_bearer' => $token,
            ]
        );

        $this->assertResponseStatusCodeSame(403);
    }

    public function test_device_view_as_anonymous(): void
    {
        $device = DeviceFactory::createOne();

        $response = static::createClient()->request(
            'GET',
            sprintf('/api/devices/%s', $device->getId()),
            [
                'headers' => ['accept' => ['application/json']],
            ]
        );

        $this->assertResponseStatusCodeSame(401);
    }
}
