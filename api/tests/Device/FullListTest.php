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

namespace App\Tests\Device;

use App\DataFixtures\Factory\DeviceFactory;
use App\DataFixtures\Factory\UserFactory;
use App\Entity\Device;
use App\Tests\TestCase;

class FullListTest extends TestCase
{
    public function test_devices_full_list(): void
    {
        $token = $this->generateToken();
        $user = UserFactory::first();
        DeviceFactory::createMany(2, fn() => ['user' => $user]);
        DeviceFactory::createMany(2);

        $response = static::createClient()->request(
            'GET',
            '/api/devices/full_list',
            [
                'headers' => ['accept' => ['application/json']],
                'auth_bearer' => $token,
            ]
        );

        $this->assertResponseIsSuccessful();
        $this->assertCount(2, $response->toArray());
        $this->assertMatchesResourceCollectionJsonSchema(Device::class, '_api_devices/full_list_get_collection', 'json');
    }

    public function test_devices_full_list_as_anonymous(): void
    {
        static::createClient()->request(
            'GET',
            '/api/devices/full_list',
            [
                'headers' => ['accept' => ['application/json']],
            ]
        );

        $this->assertResponseStatusCodeSame(401);
    }
}
