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

namespace App\Tests\Auth;

use App\DataFixtures\Factory\UserFactory;
use App\Tests\TestCase;

class RegisterTest extends TestCase
{
    public function test_register_user(): void
    {
        $this->assertEquals(0, UserFactory::count());
        static::createClient()->request(
            'POST',
            '/api/register',
            [
                'headers' => ['accept' => ['application/json']],
                'json' => [
                    'username' => 'user@example.com',
                    'password' => 'password',
                ],
            ]
        );

        $this->assertResponseIsSuccessful();
        $this->assertEquals(1, UserFactory::count());
    }
}
