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

class ChangePasswordTest extends TestCase
{
    public function test_change_password(): void
    {
        $token = $this->generateToken();
        $user = UserFactory::first();
        $device = DeviceFactory::createOne(['user' => $user, 'active' => false]);
        $oldPassword = $device->getDevicePassword();

        $response = static::createClient()->request(
            'PUT',
            sprintf('/api/devices/%s/change_password', $device->getId()),
            [
                'headers' => ['accept' => ['application/json']],
                'auth_bearer' => $token,
                'json' => [],
            ]
        );

        $this->assertResponseIsSuccessful();
        $this->assertEquals(false, $device->isActive());
    }

    public function test_change_password_as_non_owner(): void
    {
        $token = $this->generateToken();
        $device = DeviceFactory::createOne();

        $response = static::createClient()->request(
            'PUT',
            sprintf('/api/devices/%s/change_password', $device->getId()),
            [
                'headers' => ['accept' => ['application/json']],
                'auth_bearer' => $token,
                'json' => [],
            ]
        );

        $this->assertResponseStatusCodeSame(403);
    }

    public function test_change_password_as_anonymous(): void
    {
        $device = DeviceFactory::createOne();

        $response = static::createClient()->request(
            'PUT',
            sprintf('/api/devices/%s/change_password', $device->getId()),
            [
                'headers' => ['accept' => ['application/json']],
                'json' => [],
            ]
        );

        $this->assertResponseStatusCodeSame(401);
    }
}
