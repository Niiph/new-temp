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

namespace App\Tests\Auth;

use App\DataFixtures\Factory\UserFactory;
use App\Tests\TestCase;

class LoginTest extends TestCase
{
    public function test_register_user(): void
    {
        $user = UserFactory::createOne();

        $response = static::createClient()->request(
            'POST',
            '/api/login_check',
            [
                'headers' => ['accept' => ['application/json']],
                'json' => [
                    'username' => $user->getUsername(),
                    'password' => 'password',
                ],
            ]
        );

        $this->assertResponseIsSuccessful();
        $this->assertArrayHasKey('token', $response->toArray());
    }
}
