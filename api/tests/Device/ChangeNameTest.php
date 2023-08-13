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
use App\DataFixtures\Factory\UserFactory;
use App\Tests\TestCase;

class ChangeNameTest extends TestCase
{
    public function test_change_name(): void
    {
        $token = $this->generateToken();
        $user = UserFactory::first();
        $device = DeviceFactory::createOne(['user' => $user]);

        $response = static::createClient()->request(
            'PUT',
            sprintf('/api/devices/%s/change_name', $device->getId()),
            [
                'headers' => ['accept' => ['application/json']],
                'auth_bearer' => $token,
                'json' => ['name' => 'New name'],
            ]
        );

        $this->assertResponseIsSuccessful();
        $this->assertEquals('New name', $device->getName());
    }

    public function test_change_name_as_non_owner(): void
    {
        $token = $this->generateToken();
        $device = DeviceFactory::createOne();

        $response = static::createClient()->request(
            'PUT',
            sprintf('/api/devices/%s/change_name', $device->getId()),
            [
                'headers' => ['accept' => ['application/json']],
                'auth_bearer' => $token,
                'json' => ['name' => 'New name'],
            ]
        );

        $this->assertResponseStatusCodeSame(403);
    }

    public function test_change_name_as_anonymous(): void
    {
        $device = DeviceFactory::createOne();

        $response = static::createClient()->request(
            'PUT',
            sprintf('/api/devices/%s/change_name', $device->getId()),
            [
                'headers' => ['accept' => ['application/json']],
                'json' => ['name' => 'New name'],
            ]
        );

        $this->assertResponseStatusCodeSame(401);
    }
}
