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

class CreateTest extends TestCase
{
    public function test_create(): void
    {
        $token = $this->generateToken();

        $this->assertEquals(0, DeviceFactory::count());

        $response = static::createClient()->request(
            'POST',
            '/api/devices',
            [
                'headers' => ['accept' => ['application/json']],
                'auth_bearer' => $token,
                'json' => ['name' => 'Name'],
            ]
        );
        $device = DeviceFactory::first();

        $this->assertResponseIsSuccessful();
        $this->assertEquals(1, DeviceFactory::count());
        $this->assertEquals('Name', $device->getName());
    }

    public function test_create_as_anonymous(): void
    {
        $response = static::createClient()->request(
            'POST',
            '/api/devices',
            [
                'headers' => ['accept' => ['application/json']],
                'json' => ['name' => 'Name'],
            ]
        );

        $this->assertResponseStatusCodeSame(401);
    }
}
